# smart-budget-planner
Smart Budget Planner Application
📌 Table of Contents
Introduction

Features

Installation Guide

Running the Application

Google Gemini API Setup

Usage Guide

Troubleshooting

Contributing

🌟 Introduction
The Smart Budget Planner is a comprehensive financial management application that helps users track their income, expenses, and investments. With built-in AI capabilities using Google's Gemini API, it provides personalized financial advice and investment recommendations.

✨ Features
User authentication (login/register)

Income and expense tracking

Financial dashboard with charts

Transaction history with filters

AI-powered financial assistant

Investment tips and recommendations

Responsive design for all devices

🛠️ Installation Guide
Prerequisites
XAMPP (or similar stack with Apache and MySQL)

Google Gemini API Key (optional for AI features)

Web browser (Chrome/Firefox recommended)

Step-by-Step Installation
Install XAMPP

bash
# Download from https://www.apachefriends.org/download.html
# Run the installer and select these components:
# - Apache
# - MySQL
# - PHP
# - phpMyAdmin
Clone or Download the Repository

bash
git clone https://github.com/your-repo/smart-budget-planner.git
OR download the ZIP file and extract it

Place Files in htdocs

Move the project folder to:
C:\xampp\htdocs\smart-budget-planner (Windows)
/opt/lampp/htdocs/smart-budget-planner (Linux)
Start XAMPP Services

Launch XAMPP Control Panel

Start Apache and MySQL services

Create Database

Open phpMyAdmin (http://localhost/phpmyadmin)

Create new database named budget_planner

Import database.sql file from the project

🚀 Running the Application
Access the Application

Open your browser and navigate to:
http://localhost/smart-budget-planner
First-Time Setup

Register a new account

Log in with your credentials

Start adding transactions

🔑 Google Gemini API Setup
Getting API Key
Go to Google AI Studio

Sign in with your Google account

Click on "Get API Key" in the left sidebar

Create a new API key

Configuring the Application
Edit api/gemini.php

Replace this line:

php
$apiKey = 'YOUR_GEMINI_API_KEY';
with your actual API key

Save the file

API Restrictions
The AI will only answer finance-related questions

Budgeting advice

Saving strategies

Investment recommendations

No off-topic responses

📊 Usage Guide
1. Dashboard
View financial summary (income, expenses, balance)

See expense breakdown by category

Check monthly trends

View recent transactions

2. Adding Transactions
Click "Add Transaction" in the menu

Select transaction type (Income/Expense)

Enter amount, category, and description

Click "Add Transaction"

3. Viewing History
Filter transactions by type, category, or month

Edit or delete existing transactions

Export data (CSV/PDF - requires additional implementation)

4. AI Assistant
Click the chat icon in the bottom right

Ask finance-related questions like:

"How can I save more money?"

"What are good investments for beginners?"

"Tips for reducing expenses"

5. Investment Tips
Pre-generated investment recommendations

Updated daily

Based on current market trends

🐛 Troubleshooting
Common Issues
Database Connection Error

Verify XAMPP services are running

Check database credentials in includes/config.php

Ensure database exists and tables are imported

API Not Working

Verify API key is correct

Check internet connection

Ensure billing is enabled in Google Cloud Console

Page Not Loading

Check file permissions

Verify files are in correct htdocs folder

Look for errors in Apache logs

🤝 Contributing
Fork the repository

Create your feature branch (git checkout -b feature/AmazingFeature)

Commit your changes (git commit -m 'Add some AmazingFeature')

Push to the branch (git push origin feature/AmazingFeature)

Open a Pull Request

📜 License
This project is open-source and available under the MIT License.

💡 Purpose
This application helps users:

Gain control over their finances

Understand spending patterns

Get AI-powered financial advice

Make better investment decisions

Achieve financial goals

Start managing your money smarter today! 💰📈
