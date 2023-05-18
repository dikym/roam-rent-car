# Roam Rent Car Website

Roam Rent Car is a PHP-based website created as a project for the final exam (UKK) of a course. It allows users to rent cars online and provides a user-friendly interface for both admins and users to manage car rentals efficiently. This README file provides an overview of the website's features, installation instructions, and usage guidelines.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Admin Panel](#admin-panel)
- [Contributing](#contributing)
- [License](#license)

## Features

1. **User Registration and Login**: Users can create new accounts and log in securely to access their profile and manage their car rentals.

2. **Car Catalog**: The website displays an extensive catalog of available cars for rent, with details such as car type, model, price, and availability.

3. **Car Search**: Users can search for cars based on various criteria such as car type, price range, and availability dates.

4. **Booking System**: Users can select the desired car, specify rental dates, and make bookings. The system verifies availability and calculates the rental cost.

5. **User Profile**: Users can view and update their profile information, including name, username, role (admin or user), and account creation date. They can also manage their bookings and delete their account if needed.

6. **Admin Profile**: Administrators can access and update their profile information, including name, username, role (admin), and account creation date. They have additional privileges to manage the car catalog, user accounts, bookings, and other administrative tasks.

7. **Setting**: Users and admins can modify their personal information, including name, username, and password. They can also choose to delete their account.

## Installation

To install and run the Roam Rent Car website on your local development environment, follow these steps:

1. Clone the repository:

   ```bash
   git clone https://github.com/your-username/roam-rent-car.git
   ```

2. Move the cloned repository to your Apache web server's document root. For example, if you are using XAMPP, move it to the `htdocs` directory:

   ```bash
   mv roam-rent-car /path/to/xampp/htdocs/
   ```

3. Configure the database:

   - Create a new MySQL database.
   - Import the provided SQL file (`database/roam.sql`) into your database.
   - Update the database connection settings in `functions/functions.php`.

4. Start your Apache server.

5. Access the website:

   Open your web browser and visit [http://localhost/roam-rent-car](http://localhost/roam-rent-car).

   If you've installed the website in a subdirectory of your web server, adjust the URL accordingly.

   Note: Make sure Apache and MySQL are running before accessing the website.

## Usage

1. **User Registration**: Create a new user account by providing the required details.

2. **User Login**: Log in to your account with your credentials.

3. **Browse Cars**: Explore the car catalog and search for available cars based on your preferences.

4. **Make a Booking**: Select a car, specify rental dates, and proceed to make a booking. Review the booking details and confirm the payment.

5. **User Profile**: Access your personalized profile to view and update your name, username, role (admin or user), and account creation date. Manage your bookings and delete your account if needed.

## Admin Panel

Authorized administrators can access the admin panel using the provided login credentials. The admin panel offers the following functionalities:

1. **Car Management**: Add, edit, and delete cars from the catalog. Update car details such as type, model, price, and availability.

2. **Account Management**: View and manage accounts. Create new accounts, reset passwords, and view accounts details.

3. **Booking Management**: View and manage all bookings. See the booking details, including the booking date, customer name, car information, and total cost.

4. **Payment Managment**: Access payment information, including the rental ID, payment date, total payment, discount, user payment, and payment status (paid or unpaid).

5. **Admin Profile**: Access and update your profile information, including name, username, role (admin), and account creation date.

## Contributing

Contributions to the Roam Rent Car website are welcome! If you have any suggestions, bug reports, or feature requests, please open an issue on the [GitHub repository](https://github.com/dikym/roam-rent-car/issues).

If you'd like to contribute code changes, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make the necessary code changes.
4. Commit your changes and push them to your forked repository.
5. Submit a pull request with a clear description of your changes.

## License

The Roam Rent Car website is licensed under the [MIT License](LICENSE). Feel free to modify and use the code according to your needs.
