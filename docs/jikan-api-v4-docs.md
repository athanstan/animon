# Jikan REST API v4 — Complete Reference

> **Base URL:** `https://api.jikan.moe/v4`
> **Version:** 4.0.0 | **License:** MIT
> **Source:** [OpenAPI Spec](https://raw.githubusercontent.com/jikan-me/jikan-rest/master/storage/api-docs/api-docs.json) | [Docs](https://docs.api.jikan.moe)

[Jikan](https://jikan.moe) is an **Unofficial** MyAnimeList API. It scrapes the website to satisfy the need for a complete API — which MyAnimeList lacks. **Only GET requests** are supported (read-only). No authentication required.

---

## Rate Limiting

| Duration   | Requests    |
| ---------- | ----------- |
| Daily      | Unlimited   |
| Per Minute | 60 requests |
| Per Second | 3 requests  |

You may also be rate-limited by MyAnimeList itself.

---

## JSON Notes

- Any property (except arrays/objects) whose value does not exist is `null`.
- Any array/object property whose value does not exist is empty.
- Any `score` property whose value does not exist is `0`.
- All dates/timestamps are **ISO 8601** format, **UTC** timezone.

---

## Caching

All requests are cached for **24 hours**.

| Header                  | Remarks                                                                        |
| ----------------------- | ------------------------------------------------------------------------------ |
| `Expires`               | Cache expiry date                                                              |
| `Last-Modified`         | Cache set date                                                                 |
| `X-Request-Fingerprint` | Unique request fingerprint (only for single-resource requests like `/anime/1`) |

### Cache Validation

- All requests return an `ETag` header (MD5 hash of response).
- Supply it as `If-None-Match` in your next request header.
- You'll get `304 Not Modified` if content hasn't changed.

---

## HTTP Responses

| HTTP Status | Exception                                 | Remarks                           |
| ----------- | ----------------------------------------- | --------------------------------- |
| `200`       | —                                         | Success                           |
| `304`       | —                                         | Not Modified (cache validation)   |
| `400`       | BadRequestException / ValidationException | Invalid request                   |
| `404`       | BadResponseException                      | Resource not found                |
| `405`       | BadRequestException                       | Method not allowed (only GET)     |
| `429`       | RateLimitException                        | Rate limited                      |
| `500`       | UpstreamException / ParserException       | Internal server error             |
| `503`       | ServiceUnavailableException               | Service unavailable / maintenance |

### Error Response Body

```json
{
    "status": 500,
    "type": "InternalException",
    "message": "Exception Message",
    "error": "Exception Trace",
    "report_url": "https://github.com..."
}
```

---

## Common Query Parameters

| Parameter     | Type    | Description                                                                                                          |
| ------------- | ------- | -------------------------------------------------------------------------------------------------------------------- |
| `page`        | integer | Page number for paginated results                                                                                    |
| `limit`       | integer | Number of results per page                                                                                           |
| `sfw`         | boolean | Flag — filters out adult (Hentai) entries                                                                            |
| `unapproved`  | boolean | Flag — includes unapproved user-submitted entries                                                                    |
| `preliminary` | boolean | Include preliminary reviews (during ongoing anime/manga). **Required to see reviews for airing/publishing entries.** |
| `spoilers`    | boolean | Include spoiler-tagged reviews (excluded by default)                                                                 |
| `continuing`  | boolean | Flag — includes entries continuing from previous seasons                                                             |

---

## Pagination Response

Standard pagination:

```json
{
    "pagination": {
        "last_visible_page": 10,
        "has_next_page": true
    }
}
```

Extended pagination (search/collection endpoints):

```json
{
    "pagination": {
        "last_visible_page": 10,
        "has_next_page": true,
        "current_page": 1,
        "items": {
            "count": 25,
            "total": 250,
            "per_page": 25
        }
    }
}
```

---

# ENDPOINTS

---

## Anime

### Get Anime by ID

| Method | Endpoint           |
| ------ | ------------------ |
| `GET`  | `/anime/{id}`      |
| `GET`  | `/anime/{id}/full` |

**Path Parameters:** `id` (integer, required) — MyAnimeList ID

The `/full` variant returns additional fields: `relations`, `theme` (openings/endings), `external` links, and `streaming` links.

### Anime Sub-Resources

All require path parameter `id` (integer).

| Endpoint                         | Description                                           | Paginated                               |
| -------------------------------- | ----------------------------------------------------- | --------------------------------------- |
| `/anime/{id}/characters`         | Characters & voice actors                             | No                                      |
| `/anime/{id}/staff`              | Staff & positions                                     | No                                      |
| `/anime/{id}/episodes`           | Episode list                                          | Yes (`page`)                            |
| `/anime/{id}/episodes/{episode}` | Single episode by number                              | No                                      |
| `/anime/{id}/news`               | News articles                                         | Yes (`page`)                            |
| `/anime/{id}/forum`              | Forum topics                                          | No (filter: `all`, `episode`, `other`)  |
| `/anime/{id}/videos`             | Promos, episodes, music videos                        | No                                      |
| `/anime/{id}/videos/episodes`    | Episode videos                                        | Yes (`page`)                            |
| `/anime/{id}/pictures`           | Pictures                                              | No                                      |
| `/anime/{id}/statistics`         | Watching/completed/dropped stats + score distribution | No                                      |
| `/anime/{id}/moreinfo`           | Additional text info                                  | No                                      |
| `/anime/{id}/recommendations`    | User recommendations                                  | No                                      |
| `/anime/{id}/userupdates`        | Recent user list updates                              | Yes (`page`)                            |
| `/anime/{id}/reviews`            | Reviews                                               | Yes (`page`, `preliminary`, `spoilers`) |
| `/anime/{id}/relations`          | Related anime/manga                                   | No                                      |
| `/anime/{id}/themes`             | Opening & ending themes                               | No                                      |
| `/anime/{id}/external`           | External links                                        | No                                      |
| `/anime/{id}/streaming`          | Streaming links                                       | No                                      |

### Search Anime

`GET /anime`

| Parameter        | Type    | Description                                                                                                                 |
| ---------------- | ------- | --------------------------------------------------------------------------------------------------------------------------- |
| `q`              | string  | Search query                                                                                                                |
| `type`           | string  | `tv`, `movie`, `ova`, `special`, `ona`, `music`, `cm`, `pv`, `tv_special`                                                   |
| `score`          | number  | Filter by exact score                                                                                                       |
| `min_score`      | number  | Minimum score                                                                                                               |
| `max_score`      | number  | Maximum score                                                                                                               |
| `status`         | string  | `airing`, `complete`, `upcoming`                                                                                            |
| `rating`         | string  | `g`, `pg`, `pg13`, `r17`, `r`, `rx`                                                                                         |
| `sfw`            | boolean | Filter out adult entries                                                                                                    |
| `genres`         | string  | Genre IDs (comma-separated, e.g. `1,2,3`)                                                                                   |
| `genres_exclude` | string  | Exclude genre IDs (comma-separated)                                                                                         |
| `order_by`       | string  | `mal_id`, `title`, `start_date`, `end_date`, `episodes`, `score`, `scored_by`, `rank`, `popularity`, `members`, `favorites` |
| `sort`           | string  | `desc`, `asc`                                                                                                               |
| `letter`         | string  | Filter entries starting with this letter                                                                                    |
| `producers`      | string  | Producer IDs (comma-separated)                                                                                              |
| `start_date`     | string  | Format: `YYYY-MM-DD` (partial OK: `2022`, `2005-05`)                                                                        |
| `end_date`       | string  | Format: `YYYY-MM-DD`                                                                                                        |
| `page`           | integer | Page number                                                                                                                 |
| `limit`          | integer | Results per page                                                                                                            |
| `unapproved`     | boolean | Include unapproved entries                                                                                                  |

### Anime Resource Schema

```
mal_id: integer              — MyAnimeList ID
url: string                  — MAL URL
images: { jpg: { image_url, small_image_url, large_image_url }, webp: { ... } }
trailer: { youtube_id, url, embed_url }
approved: boolean
titles: [{ type, title }]    — All titles (use this, not deprecated fields)
type: "TV" | "OVA" | "Movie" | "Special" | "ONA" | "Music" | null
source: string | null        — e.g. "Manga", "Light novel", "Original"
episodes: integer | null
status: "Finished Airing" | "Currently Airing" | "Not yet aired" | null
airing: boolean
aired: { from, to, prop: { from: {day,month,year}, to: {day,month,year}, string } }
duration: string | null      — e.g. "24 min per ep"
rating: string | null        — e.g. "PG-13 - Teens 13 or older"
score: float | null
scored_by: integer | null
rank: integer | null
popularity: integer | null
members: integer | null
favorites: integer | null
synopsis: string | null
background: string | null
season: "summer" | "winter" | "spring" | "fall" | null
year: integer | null
broadcast: { day, time, timezone, string }
producers: [{ mal_id, type, name, url }]
licensors: [{ mal_id, type, name, url }]
studios: [{ mal_id, type, name, url }]
genres: [{ mal_id, type, name, url }]
explicit_genres: [{ mal_id, type, name, url }]
themes: [{ mal_id, type, name, url }]
demographics: [{ mal_id, type, name, url }]
```

**Full variant adds:**

```
relations: [{ relation: string, entry: [{ mal_id, type, name, url }] }]
theme: { openings: [string], endings: [string] }
external: [{ name, url }]
streaming: [{ name, url }]
```

---

## Manga

### Get Manga by ID

| Method | Endpoint           |
| ------ | ------------------ |
| `GET`  | `/manga/{id}`      |
| `GET`  | `/manga/{id}/full` |

### Manga Sub-Resources

| Endpoint                      | Description                                      | Paginated                               |
| ----------------------------- | ------------------------------------------------ | --------------------------------------- |
| `/manga/{id}/characters`      | Characters                                       | No                                      |
| `/manga/{id}/news`            | News articles                                    | Yes (`page`)                            |
| `/manga/{id}/forum`           | Forum topics (filter: `all`, `episode`, `other`) | No                                      |
| `/manga/{id}/pictures`        | Pictures                                         | No                                      |
| `/manga/{id}/statistics`      | Reading/completed/dropped stats                  | No                                      |
| `/manga/{id}/moreinfo`        | Additional info                                  | No                                      |
| `/manga/{id}/recommendations` | Recommendations                                  | No                                      |
| `/manga/{id}/userupdates`     | User list updates                                | Yes (`page`)                            |
| `/manga/{id}/reviews`         | Reviews                                          | Yes (`page`, `preliminary`, `spoilers`) |
| `/manga/{id}/relations`       | Related entries                                  | No                                      |
| `/manga/{id}/external`        | External links                                   | No                                      |

### Search Manga

`GET /manga`

| Parameter                           | Type    | Description                                                                                                                            |
| ----------------------------------- | ------- | -------------------------------------------------------------------------------------------------------------------------------------- |
| `q`                                 | string  | Search query                                                                                                                           |
| `type`                              | string  | `manga`, `novel`, `lightnovel`, `oneshot`, `doujin`, `manhwa`, `manhua`                                                                |
| `score` / `min_score` / `max_score` | number  | Score filters                                                                                                                          |
| `status`                            | string  | `publishing`, `complete`, `hiatus`, `discontinued`, `upcoming`                                                                         |
| `sfw`                               | boolean | Filter adult entries                                                                                                                   |
| `genres` / `genres_exclude`         | string  | Genre IDs (comma-separated)                                                                                                            |
| `order_by`                          | string  | `mal_id`, `title`, `start_date`, `end_date`, `chapters`, `volumes`, `score`, `scored_by`, `rank`, `popularity`, `members`, `favorites` |
| `sort`                              | string  | `desc`, `asc`                                                                                                                          |
| `letter`                            | string  | First letter filter                                                                                                                    |
| `magazines`                         | string  | Magazine IDs (comma-separated)                                                                                                         |
| `start_date` / `end_date`           | string  | `YYYY-MM-DD` format                                                                                                                    |
| `page`, `limit`, `unapproved`       | —       | Pagination/flags                                                                                                                       |

### Manga Resource Schema

```
mal_id, url, images, approved, titles
type: "Manga" | "Novel" | "Light Novel" | "One-shot" | "Doujinshi" | "Manhua" | "Manhwa" | "OEL" | null
chapters: integer | null
volumes: integer | null
status: "Finished" | "Publishing" | "On Hiatus" | "Discontinued" | "Not yet published"
publishing: boolean
published: { from, to, prop }
score, scored_by, rank, popularity, members, favorites
synopsis, background
authors: [{ mal_id, type, name, url }]
serializations: [{ mal_id, type, name, url }]
genres, explicit_genres, themes, demographics
```

**Full variant adds:** `relations`, `external`

---

## Characters

### Get Character by ID

| Method | Endpoint                |
| ------ | ----------------------- |
| `GET`  | `/characters/{id}`      |
| `GET`  | `/characters/{id}/full` |

### Character Sub-Resources

| Endpoint                    | Description                   |
| --------------------------- | ----------------------------- |
| `/characters/{id}/anime`    | Anime appearances (with role) |
| `/characters/{id}/manga`    | Manga appearances (with role) |
| `/characters/{id}/voices`   | Voice actors                  |
| `/characters/{id}/pictures` | Pictures                      |

### Search Characters

`GET /characters`

| Parameter       | Type    | Values                        |
| --------------- | ------- | ----------------------------- |
| `q`             | string  | Search query                  |
| `order_by`      | string  | `mal_id`, `name`, `favorites` |
| `sort`          | string  | `desc`, `asc`                 |
| `letter`        | string  | First letter filter           |
| `page`, `limit` | integer | Pagination                    |

### Character Schema

```
mal_id, url, images (jpg+webp: image_url, small_image_url)
name: string
name_kanji: string | null
nicknames: [string]
favorites: integer
about: string | null
```

**Full variant adds:** `anime`, `manga`, `voices` arrays.

---

## People

### Get Person by ID

| Method | Endpoint            |
| ------ | ------------------- |
| `GET`  | `/people/{id}`      |
| `GET`  | `/people/{id}/full` |

### People Sub-Resources

| Endpoint                | Description           |
| ----------------------- | --------------------- |
| `/people/{id}/anime`    | Anime staff positions |
| `/people/{id}/voices`   | Voice acting roles    |
| `/people/{id}/manga`    | Published manga works |
| `/people/{id}/pictures` | Pictures              |

### Search People

`GET /people`

| Parameter       | Type    | Values                                    |
| --------------- | ------- | ----------------------------------------- |
| `q`             | string  | Search query                              |
| `order_by`      | string  | `mal_id`, `name`, `birthday`, `favorites` |
| `sort`          | string  | `desc`, `asc`                             |
| `letter`        | string  | First letter filter                       |
| `page`, `limit` | integer | Pagination                                |

### Person Schema

```
mal_id, url, website_url (nullable), images
name, given_name (nullable), family_name (nullable)
alternate_names: [string]
birthday: string (ISO8601) | null
favorites: integer
about: string | null
```

**Full variant adds:** `anime`, `manga`, `voices` arrays.

---

## Producers

| Method | Endpoint                   | Description                           |
| ------ | -------------------------- | ------------------------------------- |
| `GET`  | `/producers`               | List/search producers                 |
| `GET`  | `/producers/{id}`          | Get producer by ID                    |
| `GET`  | `/producers/{id}/full`     | Full producer info (+ external links) |
| `GET`  | `/producers/{id}/external` | External links                        |

### Search Producers

| Parameter       | Type    | Values                                        |
| --------------- | ------- | --------------------------------------------- |
| `q`             | string  | Search query                                  |
| `order_by`      | string  | `mal_id`, `count`, `favorites`, `established` |
| `sort`          | string  | `desc`, `asc`                                 |
| `letter`        | string  | First letter filter                           |
| `page`, `limit` | integer | Pagination                                    |

---

## Seasons

| Method | Endpoint                   | Description                                            |
| ------ | -------------------------- | ------------------------------------------------------ |
| `GET`  | `/seasons/now`             | Current season's anime                                 |
| `GET`  | `/seasons/upcoming`        | Upcoming season's anime                                |
| `GET`  | `/seasons/{year}/{season}` | Specific season (`winter`, `spring`, `summer`, `fall`) |
| `GET`  | `/seasons`                 | List of all available seasons                          |

### Season Filters

| Parameter       | Type    | Description                                      |
| --------------- | ------- | ------------------------------------------------ |
| `filter`        | string  | `tv`, `movie`, `ova`, `special`, `ona`, `music`  |
| `sfw`           | boolean | Filter adult entries                             |
| `unapproved`    | boolean | Include unapproved entries                       |
| `continuing`    | boolean | Include continuing entries from previous seasons |
| `page`, `limit` | integer | Pagination                                       |

---

## Schedules

`GET /schedules`

| Parameter       | Type    | Values                                                                                           |
| --------------- | ------- | ------------------------------------------------------------------------------------------------ |
| `filter`        | string  | `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `unknown`, `other` |
| `kids`          | string  | `true` / `false` — filter Kids genre entries                                                     |
| `sfw`           | string  | `true` / `false` — filter Hentai entries                                                         |
| `unapproved`    | boolean | Include unapproved                                                                               |
| `page`, `limit` | integer | Pagination                                                                                       |

---

## Top

| Method | Endpoint          | Extra Parameters                                                                                      |
| ------ | ----------------- | ----------------------------------------------------------------------------------------------------- |
| `GET`  | `/top/anime`      | `type`, `filter` (`airing`, `upcoming`, `bypopularity`, `favorite`), `rating`, `sfw`, `page`, `limit` |
| `GET`  | `/top/manga`      | `type`, `filter` (`publishing`, `upcoming`, `bypopularity`, `favorite`), `page`, `limit`              |
| `GET`  | `/top/people`     | `page`, `limit`                                                                                       |
| `GET`  | `/top/characters` | `page`, `limit`                                                                                       |
| `GET`  | `/top/reviews`    | `type` (`anime`, `manga`), `preliminary`, `spoilers`, `page`                                          |

---

## Random

| Endpoint                 | Returns             |
| ------------------------ | ------------------- |
| `GET /random/anime`      | Random anime        |
| `GET /random/manga`      | Random manga        |
| `GET /random/characters` | Random character    |
| `GET /random/people`     | Random person       |
| `GET /random/users`      | Random user profile |

No parameters required.

---

## Genres

| Endpoint            | Description  |
| ------------------- | ------------ |
| `GET /genres/anime` | Anime genres |
| `GET /genres/manga` | Manga genres |

Optional `filter` parameter: `genres`, `explicit_genres`, `themes`, `demographics`

### Genre Schema

```json
{ "mal_id": 1, "name": "Action", "url": "...", "count": 5000 }
```

---

## Magazines

`GET /magazines`

| Parameter       | Type    | Description               |
| --------------- | ------- | ------------------------- |
| `q`             | string  | Search query              |
| `order_by`      | string  | `mal_id`, `name`, `count` |
| `sort`          | string  | `desc`, `asc`             |
| `letter`        | string  | First letter filter       |
| `page`, `limit` | integer | Pagination                |

---

## Clubs

| Method | Endpoint                | Description                    |
| ------ | ----------------------- | ------------------------------ |
| `GET`  | `/clubs`                | Search clubs                   |
| `GET`  | `/clubs/{id}`           | Get club by ID                 |
| `GET`  | `/clubs/{id}/members`   | Club members (paginated)       |
| `GET`  | `/clubs/{id}/staff`     | Club staff                     |
| `GET`  | `/clubs/{id}/relations` | Related anime/manga/characters |

### Search Clubs

| Parameter       | Type    | Values                                                                                                                                                      |
| --------------- | ------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `q`             | string  | Search query                                                                                                                                                |
| `type`          | string  | `public`, `private`, `secret`                                                                                                                               |
| `category`      | string  | `anime`, `manga`, `actors_and_artists`, `characters`, `cities_and_neighborhoods`, `companies`, `conventions`, `games`, `japan`, `music`, `other`, `schools` |
| `order_by`      | string  | `mal_id`, `name`, `members_count`, `created`                                                                                                                |
| `sort`          | string  | `desc`, `asc`                                                                                                                                               |
| `letter`        | string  | First letter filter                                                                                                                                         |
| `page`, `limit` | integer | Pagination                                                                                                                                                  |

---

## Users

### Get User Profile

| Method | Endpoint                 |
| ------ | ------------------------ |
| `GET`  | `/users/{username}`      |
| `GET`  | `/users/{username}/full` |

### User Sub-Resources

| Endpoint                            | Description                            | Paginated                            |
| ----------------------------------- | -------------------------------------- | ------------------------------------ |
| `/users/{username}/statistics`      | Anime & manga stats                    | No                                   |
| `/users/{username}/favorites`       | Favorite anime/manga/characters/people | No                                   |
| `/users/{username}/userupdates`     | Recent list updates                    | No                                   |
| `/users/{username}/about`           | About (raw HTML from BBCode)           | No                                   |
| `/users/{username}/history`         | Watch/read history (past 30 days)      | No (filter `type`: `anime`, `manga`) |
| `/users/{username}/friends`         | Friends list                           | Yes (`page`)                         |
| `/users/{username}/reviews`         | User reviews                           | Yes (`page`)                         |
| `/users/{username}/recommendations` | User recommendations                   | Yes (`page`)                         |
| `/users/{username}/clubs`           | User's clubs                           | Yes (`page`)                         |
| `/users/{username}/external`        | External links                         | No                                   |

### Search Users

`GET /users`

| Parameter           | Type    | Values                               |
| ------------------- | ------- | ------------------------------------ |
| `q`                 | string  | Search query                         |
| `gender`            | string  | `any`, `male`, `female`, `nonbinary` |
| `location`          | string  | Location filter                      |
| `minAge` / `maxAge` | integer | Age range                            |
| `page`, `limit`     | integer | Pagination                           |

### Get User by ID

`GET /users/userbyid/{id}` — Returns `{ url, username }`

### Deprecated Endpoints

- `GET /users/{username}/animelist` — Discontinued since May 1, 2022
- `GET /users/{username}/mangalist` — Discontinued since May 1, 2022

---

## Recommendations

| Endpoint                     | Description                              |
| ---------------------------- | ---------------------------------------- |
| `GET /recommendations/anime` | Recent anime recommendations (paginated) |
| `GET /recommendations/manga` | Recent manga recommendations (paginated) |

---

## Reviews

| Endpoint             | Description                                              |
| -------------------- | -------------------------------------------------------- |
| `GET /reviews/anime` | Recent anime reviews (`page`, `preliminary`, `spoilers`) |
| `GET /reviews/manga` | Recent manga reviews (`page`, `preliminary`, `spoilers`) |

---

## Watch

| Endpoint                      | Description                             |
| ----------------------------- | --------------------------------------- |
| `GET /watch/episodes`         | Recently added episodes                 |
| `GET /watch/episodes/popular` | Popular episodes                        |
| `GET /watch/promos`           | Recently added promo videos (paginated) |
| `GET /watch/promos/popular`   | Popular promo videos                    |

---

## Common Schemas Reference

### Images

**anime_images / manga_images:**

```json
{
    "jpg": {
        "image_url": "...",
        "small_image_url": "...",
        "large_image_url": "..."
    },
    "webp": {
        "image_url": "...",
        "small_image_url": "...",
        "large_image_url": "..."
    }
}
```

### mal_url (used for genres, producers, studios, etc.)

```json
{ "mal_id": 1, "type": "anime", "name": "...", "url": "..." }
```

### title

```json
{ "type": "Default", "title": "Cowboy Bebop" }
```

### trailer

```json
{
    "youtube_id": "...",
    "url": "https://www.youtube.com/watch?v=...",
    "embed_url": "https://www.youtube.com/embed/...",
    "images": {
        "image_url": "...",
        "small_image_url": "...",
        "medium_image_url": "...",
        "large_image_url": "...",
        "maximum_image_url": "..."
    }
}
```

### daterange

```json
{
    "from": "1998-04-03T00:00:00+00:00",
    "to": "1999-04-24T00:00:00+00:00",
    "prop": {
        "from": { "day": 3, "month": 4, "year": 1998 },
        "to": { "day": 24, "month": 4, "year": 1999 },
        "string": "Apr 3, 1998 to Apr 24, 1999"
    }
}
```

### broadcast

```json
{
    "day": "Saturdays",
    "time": "01:00",
    "timezone": "Asia/Tokyo",
    "string": "Saturdays at 01:00 (JST)"
}
```

### Review Schema (anime_review)

```json
{
    "mal_id": 1234,
    "url": "...",
    "type": "anime",
    "reactions": {
        "overall": 10,
        "nice": 5,
        "love_it": 2,
        "funny": 1,
        "confusing": 0,
        "informative": 1,
        "well_written": 1,
        "creative": 0
    },
    "date": "2023-01-15T...",
    "review": "Review text...",
    "score": 8,
    "tags": ["Recommended"],
    "is_spoiler": false,
    "is_preliminary": false,
    "episodes_watched": 24
}
```

---

## Rating Values Reference

| Code   | Meaning                        |
| ------ | ------------------------------ |
| `g`    | G - All Ages                   |
| `pg`   | PG - Children                  |
| `pg13` | PG-13 - Teens 13 or older      |
| `r17`  | R - 17+ (violence & profanity) |
| `r`    | R+ - Mild Nudity               |
| `rx`   | Rx - Hentai                    |

---

## Quick Examples

```bash
# Search anime
curl "https://api.jikan.moe/v4/anime?q=naruto&type=tv&status=complete&order_by=score&sort=desc"

# Get anime by ID (full)
curl "https://api.jikan.moe/v4/anime/1/full"

# Current season TV anime
curl "https://api.jikan.moe/v4/seasons/now?filter=tv&sfw"

# Top anime by popularity
curl "https://api.jikan.moe/v4/top/anime?filter=bypopularity&limit=10"

# Search manga
curl "https://api.jikan.moe/v4/manga?q=one+piece&type=manga"

# Get character
curl "https://api.jikan.moe/v4/characters/1/full"

# User profile
curl "https://api.jikan.moe/v4/users/Nekomata1037"

# Random anime
curl "https://api.jikan.moe/v4/random/anime"

# Anime schedule for Monday
curl "https://api.jikan.moe/v4/schedules?filter=monday"

# Anime genres list
curl "https://api.jikan.moe/v4/genres/anime"
```
