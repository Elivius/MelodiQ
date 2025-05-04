# 🎵 MelodiQ – Interactive Music Quiz Web App

**MelodiQ** is a web-based interactive quiz platform where users guess songs from short audio clips. It provides a fun, immersive, and educational experience for users of all ages, allowing them to test their music knowledge across genres and artists through a gamified interface.

---

## 📌 Introduction

MelodiQ is designed to challenge users’ familiarity with music by presenting short audio clips and asking them to guess the song titles. It offers personalized quizzes, dynamic content generation, and a gamified user experience. With an admin panel for content management and analytics, MelodiQ combines entertainment with data-driven insights.

---

## 🎯 Objectives

1. **Enhance Music Knowledge**  
   Provide an engaging platform for users to test their knowledge across various genres and artists.

2. **Personalised Quiz Experience**  
   Allow users to select artists and difficulty levels for a tailored quiz.

3. **Dynamic Content Generation**  
   Pull real-time questions from a growing song and artist database.

4. **Admin Content Management**  
   Enable admins to manage users, content, and system insights through a secure dashboard.

5. **Responsive Design**  
   Ensure full functionality across all devices – desktop, tablet, and mobile.

6. **Secure & Scalable Backend**  
   Implement PHP and MySQL to handle core logic, storage, and leaderboards securely.

7. **User-Friendly Navigation**  
   Deliver an intuitive interface for smooth interaction.

8. **Gamification Features**  
   Real-time scoring, time-based rankings, and leaderboards for engagement.

9. **Analytics & Reporting**  
   Admin dashboard insights on user activity, quiz trends, and popular content.

---

## 📦 Scope

### 🔧 Core Functionalities
- Audio-based quiz gameplay
- Custom quiz settings (artist & difficulty)
- Score tracking & leaderboard

### 💻 Technical Implementation
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL

### 🔐 Security Measures
- User authentication & access control
- Data sanitisation & prepared statements
- CSRF token implementation
- Password hashing with BCRYPT

### 🎧 Multimedia & Interactivity
- Dynamic audio integration
- Real-time feedback & score updates

---

## 👤 End-User Specification

### 🎯 Target Audience
- **Casual Music Fans** – Explore and enjoy music knowledge
- **Competitive Players** – Challenge themselves and climb leaderboards
- **Educators** – Use as an educational tool or trivia challenge

### ⚙️ Functional Requirements

#### 🎼 Audio-Based Quiz
- Multiple-choice song guessing
- Time-based answer tracking
- Immediate correct/incorrect feedback

#### ⚙️ Custom Quiz Settings
- Select specific artists
- Choose difficulty: Beginner (full clip) or Expert (karaoke-style)

#### 🏆 Score & Ranking System
- Ranked by accuracy & time
- Top 3 leaderboard display
- Personal score overview

#### 👥 User Accounts
- Secure login/registration
- Track quiz history, play time, registration date

#### 🔁 Dynamic Content Generation
- Auto-update quiz questions from database
- Reflect new content added by admin
- Analyse top-played artists for ‘Hits Quizzes’

#### 🛠️ Admin Panel
- Manage users, songs, artists
- View stats (e.g. most/least popular artists, user engagement)
- View and respond to user feedback

#### 💬 Feedback Mechanism
- Users can submit suggestions or report issues

---

## 📋 Non-Functional Requirements

- **Security**
  - Protect user data with encryption and validation
  - Prevent XSS, SQL Injection, CSRF

- **Usability**
  - Intuitive interface
  - Easy navigation for both users and admins

- **Responsiveness**
  - Fully responsive across desktop, tablet, and mobile

- **Technology Stack**
  - **Frontend:** HTML, CSS, JavaScript
  - **Backend:** PHP
  - **Database:** MySQL

---

## 🔍 Major Functions of MelodiQ

### 🎨 UI/UX
- Audio playback
- Multiple-choice question presentation
- Responsive quiz interface
- Leaderboard and results display
- Quiz customisation options

### 🗃️ Data Handling
- Store/retrieve user, quiz, and song data
- Leaderboard management
- Admin CRUD operations on users, songs, artists

### 🔐 Security
- Authentication & access control
- Input validation & sanitisation
- Prevention of unauthorised access

### 📡 Communication
- Deliver quiz content and feedback
- Enable admin data monitoring and analytics

---

## 📫 Feedback and Contribution

We welcome suggestions and feedback! Please open an issue or submit a pull request to contribute to MelodiQ.

---

## 🧑‍💻 Developed With

- PHP
- MySQL
- HTML, CSS, JavaScript

---

## 📃 License

This project is for academic use and demonstration purposes only.
