# Product Price Exporter

Export product prices from eCommerce sites to .csv file.

Results file contains rows: product url, title, price.

# Usage

Configure settings
---

```text
cp config.php.dist config.php
vim config.php
```

Make an example file in .csv format for teaching an application
---

Each line require follow information:
- url to product, ex: https://store.com/product_1.html
- title, ex: "Luxur super-duper product"
- price, ex: 1.99

Initialization an application
---

```text
php cli.php init
```

Teach an application
---

```text
php cli.php learn <path_to_examples.csv>
```

Store products
---

```text
php cli.php load-product
```

Store a revision of prices
---

```text
php cli.php load-price <number_of_revision>
```

Export products with prices to .csv file
---

```text
php cli.php export <number_of_revision> > <path_to_result.csv>
```