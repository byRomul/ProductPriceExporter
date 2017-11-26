# Product Price Exporter

Exports product prices from eCommerce sites to .csv file.

This file contains the following information: product url, title, price.

# Usage

Configure settings
---

```text
cp config.php.dist config.php
vim config.php
```

Make a sample file in .csv format to train the application
---

Each line requires the following information:
- product url, e.g.: https://store.com/product_1.html
- title, e.g.: "Luxur super-duper product"
- price, e.x.: 1.99

Initialize the application
---

```text
php cli.php init
```

Train the application
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