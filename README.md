# TechALearn

TechALearn is a modern Learning Management System (LMS) built with Laravel 12. It provides a comprehensive platform for programming education, featuring role-based access for students and lecturers, automated placement testing, course management, and certification.

## Features

### General
*   **Authentication**: Secure login and registration system.
*   **Role-Based Access Control**: Distinct dashboards and permissions for Students and Lecturers.
*   **Responsive Design**: Built with TailwindCSS for a seamless experience across devices.

### For Students
*   **Placement Test**: an adaptive testing system that determines the student's skill level (Pemula, Menengah, Mahir) using a randomized question bank.
*   **Learning Dashboard**: Track progress across enrolled modules.
*   **Course Enrollment**: Browse and enroll in available programming modules.
*   **Interactive Learning**: Access lessons containing rich text, video, and resources.
*   **Quizzes**: Take quizzes to test knowledge retention.
*   **Project Submission**: Upload final projects for lecturer review.
*   **Certification**: Generate digital certificates upon module completion.

### For Lecturers
*   **Module Management**: Create, edit, and manage learning modules.
*   **Lesson Planning**: Organize content into structured lessons.
*   **Quiz Builder**: Create quizzes with multiple-choice questions.
*   **Bank Soal (Question Bank)**: A centralized repository for placement test questions. Lecturers can contribute questions with varying difficulty levels (Easy, Medium, Hard).
*   **Student Management**: Monitor student progress and enrollment.
*   **Grading**: Review and grade student project submissions with feedback.

## Technology Stack

*   **Backend**: Laravel 12, PHP 8.2+
*   **Frontend**: Blade Templates, TailwindCSS 4, Alpine.js
*   **Database**: MySQL / SQLite
*   **Build Tools**: Vite, Composer, NPM

## Installation

Follow these steps to set up the project locally:

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/your-username/techalearn.git
    cd techalearn
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install Node.js Dependencies**
    ```bash
    npm install
    ```

4.  **Environment Setup**
    Copy the example environment file and configure your database settings:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Database Migration and Seeding**
    Run the migrations to set up the database schema and seed it with initial data (roles, test users, and question bank).
    ```bash
    php artisan migrate:fresh --seed
    ```
    *Note: The default seeder includes the BankSoalSeeder which populates the placement test questions.*

6.  **Build Frontend Assets**
    ```bash
    npm run build
    ```

7.  **Run the Application**
    Start the local development server:
    ```bash
    php artisan serve
    ```

## Usage

### Default Accounts
The database seeding process creates two default accounts for testing:

*   **Lecturer Account**
    *   Email: `teacher@techalearn.com`
    *   Password: `password`

*   **Student Account**
    *   Email: `student@techalearn.com`
    *   Password: `password`

### Workflow

1.  **Placement Test**: New students are required to take a placement test. The system randomly selects 10 active questions from the Bank Soal matches.
2.  **Course Creation**: Lecturers create modules and populate them with lessons and quizzes.
3.  **Enrollment**: Students based on their level or interest enroll in modules.
4.  **Completion**: Students complete all lessons, pass quizzes, and submit a final project to receive a certificate.

## Project Structure

*   `app/Http/Controllers`: Backend logic for handling requests (Modules, Lessons, Placement, etc.).
*   `app/Models`: Database models (User, Module, Lesson, Quiz, BankSoal).
*   `database/migrations`: Database schema definitions.
*   `database/seeders`: Initial data population.
*   `resources/views`: Blade templates for the frontend UI.
*   `routes/web.php`: Application route definitions.

## Contributing

1.  Fork the repository.
2.  Create a feature branch.
3.  Commit your changes.
4.  Push to the branch.
5.  Open a Pull Request.

## License

This project is open-sourced software licensed under the MIT license.
