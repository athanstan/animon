# 🎌 Animon

**Track your anime journey. Connect with friends. Never lose progress.**

Animon is an anime tracking platform that lets users manage their watchlists, track episode progress, create custom showcase lists, and get real-time notifications when friends update their activity.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16+-4169E1?style=flat-square&logo=postgresql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## ✨ Features

### Core Tracking

-   **Status Tracking** — Mark anime as Watching, Completed, Plan to Watch, On Hold, or Dropped
-   **Episode Progress** — Track individual episodes watched with timestamps
-   **Scoring** — Rate anime on a 1-10 scale

### Custom Lists

-   **Showcase Lists** — Create up to 3 custom lists (free tier) to curate your favorites
-   **Flexible Organization** — "Top 10 Isekai", "Comfort Watches", "Hidden Gems" — you decide

### Social

-   **Follow Friends** — Keep up with what your friends are watching
-   **Real-time Notifications** — WebSocket-powered updates when friends complete anime or hit milestones
-   **Activity Feed** — See recent activity from people you follow

### Premium Features (Coming Soon)

-   Unlimited custom lists
-   List analytics & insights
-   Advanced progress statistics
-   Export data

---

## 🛠 Tech Stack

| Layer          | Technology                                                      |
| -------------- | --------------------------------------------------------------- |
| **Backend**    | Laravel 12.x                                                    |
| **Database**   | PostgreSQL 16+                                                  |
| **Cache**      | Redis                                                           |
| **Queue**      | Laravel Queue (Redis/Database)                                  |
| **WebSockets** | Laravel Reverb / Pusher                                         |
| **API Client** | [Saloon PHP](https://docs.saloon.dev/)                          |
| **Anime Data** | [Jikan API v4](https://jikan.moe/) (Unofficial MyAnimeList API) |

---

## 📐 Architecture

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   Client App    │────▶│  Laravel API    │────▶│   PostgreSQL    │
│  (Web/Mobile)   │     │                 │     │  (User Data)    │
└─────────────────┘     └────────┬────────┘     └─────────────────┘
                                 │
                                 ▼
                        ┌─────────────────┐
                        │   Jikan API     │
                        │ (Anime Source)  │
                        └─────────────────┘
```

**Key Principle:** User data (lists, progress, follows) lives in our database. Anime metadata is fetched from Jikan API and cached locally.

---

## 🚀 Installation

### Prerequisites

-   PHP 8.2+
-   Composer
-   PostgreSQL 16+
-   Redis (recommended)
-   Node.js 18+ (for frontend assets)

### Setup

```bash
# Clone the repository
git clone https://github.com/yourusername/animon.git
cd animon

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=animon
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# (Optional) Import anime cache from Jikan API
php artisan anime:import

# Start the queue worker (separate terminal)
php artisan queue:work

# Start development server
php artisan serve
```

---

## 📊 Database Schema

### Tracking Layer

```
anime_user          — User's anime with status (watching, completed, etc.)
episode_user        — Individual episodes watched (progress tracking)
```

### Curation Layer

```
anime_lists         — Custom user-created showcase lists
anime_list_entries  — Anime entries within custom lists
```

### Social Layer

```
follows             — User follow relationships
activity_log        — Activity feed for notifications
```

### Cache Layer

```
anime_cache         — Local cache of Jikan API anime data
```

---

## 🔌 API Endpoints

### Anime

```
GET    /api/anime/search?q={query}     Search anime
GET    /api/anime/{mal_id}             Get anime details
```

### User Lists

```
GET    /api/lists                      Get user's tracking lists
POST   /api/anime/{mal_id}/status      Add/update anime status
PATCH  /api/anime/{mal_id}/progress    Update episode progress
```

### Custom Lists

```
GET    /api/lists/custom               Get user's custom lists
POST   /api/lists/custom               Create custom list
POST   /api/lists/custom/{id}/anime    Add anime to list
DELETE /api/lists/custom/{id}          Delete custom list
```

### Social

```
GET    /api/feed                       Get activity feed
POST   /api/users/{id}/follow          Follow a user
DELETE /api/users/{id}/follow          Unfollow a user
GET    /api/users/{id}/followers       Get user's followers
GET    /api/users/{id}/following       Get who user follows
```

---

## ⚡ Rate Limiting

Jikan API has rate limits we respect:

-   **3 requests/second**
-   **60 requests/minute**

The Saloon connector handles this automatically via the rate limit plugin.

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature
```

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## 🙏 Acknowledgments

-   [Jikan API](https://jikan.moe/) — For providing free access to anime data
-   [MyAnimeList](https://myanimelist.net/) — The source of anime information
-   [Saloon PHP](https://docs.saloon.dev/) — Elegant API integration library
-   [Laravel](https://laravel.com/) — The PHP framework for web artisans

---

<p align="center">
  Made with ❤️ for anime fans everywhere
</p>
