# Laravel Simple Calculator

This project showcases a simple calculator application developed using Laravel, following the MVC architecture for clean code organization. It offers a user-friendly interface with real-time calculation capabilities, ensuring a seamless user experience. Server-side validation and data transfer objects maintain data integrity and security, while the calculator service encapsulates calculation logic in a modular and testable manner.

It serves as a practical example of building web applications with Laravel, demonstrating features like routing, controller logic, form request validation, and service implementation. Easily extendable and customizable.

## Features

- **Calculator Interface:** Provides a sleek and intuitive calculator interface for users to input arithmetic expressions.
- **Real-time Calculation:** Calculates the result instantly as users input expressions.
- **Error Handling:** Handles edge cases such as division by zero or invalid input gracefully.
- **Data Validation:** Validates user input to ensure data integrity and security.
- **Modular Code Structure:** Follows the MVC (Model-View-Controller) architecture for clean and organized code.

## Installation

1. Clone the repository:
2. composer install
3. cp .env.example .env
4. php artisan key:generate
5. php artisan serve

6. Access the application in your browser at `http://127.0.0.1:8000`.

## Technologies Used

- Laravel
- Tailwind CSS
- JavaScript
- toastr.js

## Contributing

Contributions are welcome! Feel free to submit issues or pull requests for any improvements or features you'd like to see.

## License

This project is licensed under the [MIT License](LICENSE).
