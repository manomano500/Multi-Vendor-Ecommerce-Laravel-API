#!/bin/bash

# Create necessary directories
mkdir -p organized_data/stores
mkdir -p organized_data/categories
mkdir -p organized_data/products

# Parse the JSON file and organize data
jq -r '.stores[] | @base64' fakestore_data.json | while read -r store; do
    _jq() {
        echo "${store}" | base64 --decode | jq -r "${1}"
    }

    store_name=$(_jq '.name' | tr ' ' '_')
    mkdir -p "organized_data/stores/${store_name}"
    cp "database/data/fakestore_data/$(_jq '.image')" "organized_data/stores/${store_name}/"
done

jq -r '.categories[] | @base64' fakestore_data.json | while read -r category; do
    _jq() {
        echo "${category}" | base64 --decode | jq -r "${1}"
    }

    category_name=$(_jq '.name')
    mkdir -p "organized_data/categories/${category_name}"
    cp -r "database/data/fakestore_data/${category_name}" "organized_data/categories/"
done

jq -r '.products[] | @base64' fakestore_data.json | while read -r product; do
    _jq() {
        echo "${product}" | base64 --decode | jq -r "${1}"
    }

    product_name=$(_jq '.title' | tr ' ' '_')
    category=$(_jq '.category')
    mkdir -p "organized_data/products/${category}/${product_name}"

    for image in $(_jq '.images[]'); do
        cp "database/data/fakestore_data/${category}/${image}" "organized_data/products/${category}/${product_name}/"
    done
done

# Modify the EcommerceSeeder.php file
sed -i 's|$dataPath = database_path('"'"'data/fakestore_data'"'"');|$dataPath = database_path('"'"'data/organized_data'"'"');|' database/seeders/EcommerceSeeder.php

sed -i 's|$jsonFilePath = $dataPath . '"'"'/fakestore_data.json'"'"';|$jsonFilePath = database_path('"'"'data/fakestore_data.json'"'"');|' database/seeders/EcommerceSeeder.php

# Update image paths in the seeder
sed -i 's|$imagePath = $dataPath . '"'"'/'"'"' . $storeData['"'"'image'"'"'];|$imagePath = $dataPath . '"'"'/stores/'"'"' . str_replace('"'"' '"'"', '"'"'_'"'"', $storeData['"'"'name'"'"']) . '"'"'/'"'"' . basename($storeData['"'"'image'"'"']);|' database/seeders/EcommerceSeeder.php

sed -i 's|$imagePath = $dataPath . '"'"'/'"'"' . $categoryData['"'"'folder'"'"'] . '"'"'/'"'"' . basename($imageUrl);|$imagePath = $dataPath . '"'"'/products/'"'"' . $categoryData['"'"'name'"'"'] . '"'"'/'"'"' . str_replace('"'"' '"'"', '"'"'_'"'"', $productData['"'"'title'"'"']) . '"'"'/'"'"' . basename($imageUrl);|' database/seeders/EcommerceSeeder.php

# Add store-category relationship logic
sed -i '/Create a store/,/]);/c\                // Create a store\n                $store = Store::create([\n                    '"'"'name'"'"' => $storeData['"'"'name'"'"'],\n                    '"'"'description'"'"' => $storeData['"'"'description'"'"'],\n                    '"'"'user_id'"'"' => $user->id,\n                    '"'"'category_id'"'"' => $category,\n                    '"'"'image'"'"' => $this->storeImage($dataPath . '"'"'/stores/'"'"' . str_replace('"'"' '"'"', '"'"'_'"'"', $storeData['"'"'name'"'"']) . '"'"'/'"'"' . basename($storeData['"'"'image'"'"']), $storeData['"'"'name'"'"'], 0),\n                    '"'"'address'"'"' => '"'"'123 Main St'"'"',\n                    '"'"'status'"'"' => '"'"'active'"'"',\n                ]);' database/seeders/EcommerceSeeder.php

echo "Data organization and seeder modification completed."
