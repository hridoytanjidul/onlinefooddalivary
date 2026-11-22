# HostelMania – Online Food Delivery

A PHP and MySQL food-ordering website. Customers browse restaurants, add dishes to a cart and order with cash on delivery. An admin panel manages restaurants, categories, menus, users and order status.

## Features

**Customers**
- Register and log in
- Browse restaurants, filtered by category
- View a restaurant's menu and add dishes to the cart
- Check out with cash on delivery
- Track orders on *My Orders* (Dispatch → On the way → Delivered / Cancelled)
- Cancel an order until the restaurant starts on it

**Admin panel** (`/admin`)
- Dashboard with restaurant, dish, user and order counts, plus total earnings
- Add, edit and delete restaurants, categories, dishes (with images) and users
- View each order and update its status with a message for the customer

## Requirements

- PHP 7.4 or newer with the `mysqli` extension (tested on PHP 8.2)
- MySQL or MariaDB
- A local server bundle such as [XAMPP](https://www.apachefriends.org/) is the easiest way to get both

## Setup with XAMPP

1. Copy the `HostelMania-PHP` folder into `xampp/htdocs/`.
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Open phpMyAdmin at <http://localhost/phpmyadmin>, create a database named `onlinefoodphp`, and import `HostelMania-PHP/DATABASE FILE/onlinefoodphp.sql`.
4. If your MySQL login is not `root` with an empty password, update it in `HostelMania-PHP/connection/connect.php`.
5. Open the site:
   - Shop: <http://localhost/HostelMania-PHP/>
   - Admin panel: <http://localhost/HostelMania-PHP/admin/>

Uploaded images are saved in `admin/Res_img/` (restaurants) and `admin/Res_img/dishes/` (dishes). The web server needs write access to both folders.

## Setup with Docker (no XAMPP needed)

With [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed, run this in the project's root folder:

```bash
docker compose up -d
```

Then open <http://localhost:8080> for the shop and <http://localhost:8080/admin/> for the admin panel. The sample database is imported automatically the first time.

- Stop the site: `docker compose stop`
- Start it again: `docker compose start`
- Reset to a fresh sample database (deletes local orders, users and so on): `docker compose down -v`

## Deploying to a web host (e.g. InfinityFree)

1. Create a MySQL database in your hosting control panel and import `DATABASE FILE/onlinefoodphp.sql` into it with phpMyAdmin.
2. Copy `connection/config.example.php` to `connection/config.php` and fill in the database host, username, password and database name from your host. `config.php` is ignored by git, so your password never ends up on GitHub.
3. Upload the **contents** of the `HostelMania-PHP` folder into the site's web root (`htdocs/` on InfinityFree), using the host's file manager or an FTP client such as FileZilla.
   - Don't upload the `DATABASE FILE` folder or the `01 LOGIN DETAILS & PROJECT INFO.txt` files, since anyone could download them from a live site.
4. **Change the admin password.** The demo password below is public, so set a new one on any live site. In phpMyAdmin, run:
   ```sql
   UPDATE admin SET password = MD5('your-new-password') WHERE username = 'admin';
   ```
   The site replaces this with a secure hash the first time you log in.

## Demo accounts

These come with the sample database:

| Role     | Username | Password    |
|----------|----------|-------------|
| Admin    | `admin`  | `codeastro` |
| Customer | `scott`  | `password`  |

The customers `christine` and `liamoore` also use `password`. The sample data stores old MD5 password hashes; each one is replaced with a secure hash the first time that account logs in.

## Project structure

```
HostelMania-PHP/
├── index.php              Home page: popular dishes and featured restaurants
├── restaurants.php        All restaurants
├── dishes.php             A restaurant's menu and the cart
├── checkout.php           Place the order
├── your_orders.php        Customer's orders and their status
├── login.php, registration.php, logout.php
├── product-action.php     Cart actions (add, remove, empty)
├── delete_orders.php      Customer cancels a pending order
├── connection/connect.php Database connection, session and shared helpers
├── admin/                 Admin panel; auth.php protects every admin page
├── css/, js/, fonts/, images/
└── DATABASE FILE/onlinefoodphp.sql
```

## Security

- All database queries use prepared statements, and data is escaped before it is shown.
- Every admin page requires an admin login.
- Every form that changes data, including all delete buttons, carries a CSRF token.
- The session cookie is HttpOnly and SameSite=Lax.
- Passwords are hashed with PHP's `password_hash()`.

## Troubleshooting

- **"Database connection failed…"**: MySQL isn't running, or the `onlinefoodphp` database hasn't been imported yet.
- **A page is blank or something doesn't work**: PHP errors are hidden from visitors and written to the PHP error log instead. In XAMPP on Windows that is `xampp\php\logs\php_error_log`.
- **"Your session expired. Please reload the page and try again."**: the form was open for too long or in another session. Reload the page and submit again.
- **Image upload fails**: images must be JPG, PNG or GIF and under 1 MB, and the `admin/Res_img` folders must be writable.
