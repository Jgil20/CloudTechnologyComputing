 Cloud Technology Computing

[![Website](https://img.shields.io/badge/Website-cloudtechnologycomputing.com-0A66C2)](https://www.cloudtechnologycomputing.com/)
[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](#license)

Official website and digital platform for **Cloud Technology Computing Corporation**, a Texas-based cloud consulting, software development, AI automation, cybersecurity, managed IT, SEO, and digital transformation company.

**Live website:** [www.cloudtechnologycomputing.com](https://www.cloudtechnologycomputing.com/)

![Cloud Technology Computing website preview](assets/img/home-6/CloudComputing.webp)

## Table of Contents

- [Overview](#overview)
- [Core Services](#core-services)
- [Key Features](#key-features)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Local Setup](#local-setup)
- [Database Setup](#database-setup)
- [Environment Configuration](#environment-configuration)
- [SEO and Analytics](#seo-and-analytics)
- [Deployment](#deployment)
- [Security Notes](#security-notes)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## Overview

Cloud Technology Computing helps small businesses, startups, and growing organizations design, build, secure, migrate, and manage modern technology solutions.

The website is more than a static company profile. It includes dynamic service and solution pages, a database-driven blog and case-study system, SEO landing pages, lead-generation tools, analytics tracking, consultation requests, resource content, and conversion-focused business features.

The platform is designed to:

- Explain cloud, AI, web, mobile, cybersecurity, and managed IT services
- Attract organic search traffic through dedicated SEO landing pages
- Convert visitors into qualified leads
- Publish dynamic blog posts and case studies
- Support customer inquiries, estimates, assessments, and consultation requests
- Track important website interactions with Google Analytics 4
- Provide a scalable PHP and MySQL foundation for future client and administrative tools

## Core Services

- Cloud computing consulting
- AWS, Microsoft Azure, Google Cloud, and IBM Cloud planning
- Cloud migration and modernization
- Hybrid and multi-cloud architecture
- Managed cloud and IT support
- Cloud security and data protection
- Custom PHP and MySQL development
- Business website development
- AI chatbot and workflow automation
- Android and iOS mobile app development
- SEO and digital marketing
- Data analytics and reporting
- SAP analytics and consulting support

## Key Features

### Dynamic content

- Database-driven blog
- Dynamic case studies
- Reusable PHP service and solution templates
- SEO-friendly slugs and clean URLs
- Dynamic related-content and internal-link sections
- Searchable resource and glossary content

### Lead generation

- Contact and consultation forms
- Cloud readiness assessment
- Technology service cost estimator
- Proposal request builder
- Client review submission
- Lead-source and UTM attribution capture
- Private lead dashboard

### SEO

- Unique page titles and meta descriptions
- Canonical URLs
- Open Graph and Twitter Card metadata
- XML sitemap
- Search-engine-friendly URLs
- Internal linking between services, blogs, case studies, and solution pages
- JSON-LD structured data
- Service, Article, Breadcrumb, Organization, and FAQ schema where appropriate
- Optimized image formats and descriptive alternative text
- Keyword-focused cloud-computing landing pages

### Analytics

- Google Analytics 4 integration
- Page titles defined before the Google tag loads
- Explicit `page_title` and `page_location` reporting
- Conversion-ready events for forms, calls to action, assessments, and consultation requests
- Campaign tracking through UTM parameters

### User experience

- Responsive layouts for desktop, tablet, and mobile
- Accessible navigation and skip links
- Mobile-friendly calls to action
- Interactive carousels, accordions, and service cards
- Fast-loading AVIF and WebP images
- Progressive Web App assets and manifest support

## Technology Stack

### Front end

- HTML5
- CSS3
- Bootstrap
- JavaScript
- jQuery
- Font Awesome
- Owl Carousel
- Lightbox
- Responsive and mobile-first layouts

### Back end

- PHP 8.x
- MySQL or MariaDB
- PDO prepared statements
- PHP sessions
- CSRF protection
- Server-side form validation

### Infrastructure and services

- Apache or LiteSpeed
- `.htaccess` URL rewriting
- cPanel-compatible hosting
- Cloudflare
- Google Analytics 4
- Google Search Console
- Open Graph and Twitter Card metadata
- JSON-LD structured data

## Project Structure

The repository may evolve over time, but the main structure follows this pattern:

```text
root/
├── admin/                         # Administrative tools and dashboards
├── assets/
│   ├── css/                       # Theme and component styles
│   ├── fonts/                     # Theme font assets
│   ├── img/                       # Website images and media
│   └── js/                        # Theme and page scripts
├── config/
│   └── database.php               # Database connection configuration
├── css/
│   ├── business-tools.css         # Assessment, estimator, and lead-tool styles
│   ├── style.css                  # Main site styling
│   └── responsive.css             # Responsive rules
├── database/
│   ├── install_business_features.sql
│   └── *.sql                      # Database installation and content scripts
├── images/                        # Additional website images
├── js/                            # JavaScript libraries and custom scripts
├── uploads/                       # User or administrator uploads
├── about.php
├── blog.php
├── blog-details.php
├── book-consultation.php
├── case-studies.php
├── case-study-details.php
├── certifications.php
├── cloud-glossary.php
├── cloud-readiness-assessment.php
├── contact.php
├── cost-estimator.php
├── footer.php
├── form.php
├── header.php
├── index.php
├── lead-dashboard.php
├── nav.php
├── pricing.php
├── proposal-builder.php
├── resources.php
├── services.php
├── site-search.php
├── solution.php
├── testimonials.php
├── sitemap.xml
├── manifest.json
├── .htaccess
└── README.md
```

## Local Setup

### Requirements

- PHP 8.0 or newer
- MySQL 8.0+ or MariaDB
- Apache, LiteSpeed, Nginx, XAMPP, MAMP, WAMP, or a comparable local environment
- PHP extensions: PDO, PDO MySQL, JSON, mbstring, OpenSSL, and fileinfo

### 1. Clone the repository

```bash
git clone https://github.com/YOUR-USERNAME/cloudtechnologycomputing.git
cd cloudtechnologycomputing
```

Replace the example repository URL with the actual GitHub repository URL.

### 2. Create a local database

```sql
CREATE DATABASE cloudtechnologycomputing
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

### 3. Import the SQL files

Import the required schema and feature files through phpMyAdmin, MySQL Workbench, or the command line:

```bash
mysql -u root -p cloudtechnologycomputing < database/install_business_features.sql
```

Import any additional blog, case-study, or application-specific SQL files included in the `database/` directory.

### 4. Configure the database connection

Create or update `config/database.php`:

```php
<?php

declare(strict_types=1);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$database = getenv('DB_NAME') ?: 'cloudtechnologycomputing';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

$dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";

$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
```

Do not commit production credentials to Git.

### 5. Start the local server

Using PHP's built-in development server:

```bash
php -S localhost:8000
```

Then open:

```text
http://localhost:8000
```

For full `.htaccess` rewrite support, use Apache, XAMPP, MAMP, WAMP, or another compatible web server.

## Database Setup

The platform may use tables for:

- Blog categories
- Blog posts
- Blog tags
- Post views
- Case studies
- Contact submissions
- Feature leads
- Consultation requests
- Assessments
- Cost estimates
- Proposal requests
- Testimonials
- Newsletter subscriptions
- Chatbot messages

Use `utf8mb4` for full Unicode support.

Before importing production data:

1. Back up the existing database.
2. Review SQL files for table-name conflicts.
3. Confirm foreign-key relationships.
4. Test changes in staging.
5. Avoid manually changing primary-key values when dependent foreign keys exist.

## Environment Configuration

Recommended environment variables:

```env
APP_ENV=production
APP_URL=https://www.cloudtechnologycomputing.com

DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=your_database_user
DB_PASS=your_secure_database_password

LEAD_DASHBOARD_PASSWORD=use-a-long-random-password

GA4_MEASUREMENT_ID=G-XXXXXXXXXX

MAIL_FROM_ADDRESS=no-reply@cloudtechnologycomputing.com
MAIL_FROM_NAME="Cloud Technology Computing"
```

Do not expose `.env`, database backups, API keys, SMTP passwords, or private credentials through the public web directory.

## SEO and Analytics

The site follows an on-page SEO structure that includes:

- One descriptive `<title>` per page
- Titles rendered inside `<head>` before Google Analytics loads
- Unique meta descriptions
- One primary H1 per page
- Logical H2–H4 hierarchy
- Canonical URLs
- Crawlable internal links
- XML sitemap entries
- Descriptive image filenames and alt text
- Mobile-responsive layouts
- Structured data
- Open Graph and Twitter Card metadata
- Dedicated keyword landing pages
- Google Search Console monitoring

Example analytics order:

```html
<head>
    <title>Cloud Technology Services for Business | CTC</title>
    <meta
        name="description"
        content="Cloud technology services, consulting, migration, security, hosting, and managed support for growing businesses."
    >

    <!-- Google tag loads after page-specific metadata -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
</head>
```

## Deployment

### Standard hosting deployment

1. Back up the live website and database.
2. Upload the project through Git, SFTP, FTP, or the hosting control panel.
3. Keep the document root pointed to the intended public directory.
4. Configure the production database.
5. Import required SQL migrations.
6. Enable HTTPS.
7. Confirm `.htaccess` and URL rewriting.
8. Confirm file and directory permissions.
9. Clear server and CDN caches.
10. Test all forms, routes, database queries, and payment links.
11. Submit the sitemap in Google Search Console.
12. Verify GA4 events and page titles in DebugView and Realtime reports.

### Recommended production permissions

```text
Directories: 755
Files:       644
Sensitive configuration files: restrict as tightly as the host permits
```

Writable upload directories may require different permissions depending on the hosting provider. Avoid using `777`.

## Security Notes

- Use PDO prepared statements for database operations.
- Validate and sanitize all user input.
- Escape output with `htmlspecialchars`.
- Protect forms with CSRF tokens.
- Limit upload type, size, and filename handling.
- Store API keys and credentials outside public source code.
- Use HTTPS everywhere.
- Protect administrative routes with secure authentication.
- Rate-limit sensitive forms and chatbot endpoints.
- Log errors privately; do not display stack traces in production.
- Keep PHP, libraries, and hosting software updated.
- Back up the database and uploaded files regularly.

## Contributing

Contributions, bug reports, and improvement suggestions are welcome.

1. Fork the repository.
2. Create a branch:

```bash
git checkout -b feature/your-feature-name
```

3. Make and test your changes.
4. Commit with a descriptive message:

```bash
git commit -m "Add cloud readiness assessment improvements"
```

5. Push the branch:

```bash
git push origin feature/your-feature-name
```

6. Open a pull request with a clear summary, testing steps, screenshots for visual changes, database migration notes, and any security or SEO impact.

## License

This project is licensed under the MIT License unless otherwise stated in the repository.

Third-party themes, images, fonts, libraries, trademarks, and service integrations remain subject to their respective licenses and terms.

## Contact

**Jhon Arzu-Gil**  
Founder and President, Cloud Technology Computing Corporation

- Website: [www.cloudtechnologycomputing.com](https://www.cloudtechnologycomputing.com/)
- Email: [Jgil20@me.com](mailto:Jgil20@me.com)
- Phone: [713-870-9966](tel:+17138709966)
- LinkedIn: [linkedin.com/in/jhongil](https://www.linkedin.com/in/jhongil)
- Portfolio: [www.arzugil.com](https://www.arzugil.com/)
- Credentials: [Credly profile](https://www.credly.com/users/jhongil/)

---

Cloud Technology Computing Corporation builds practical cloud, AI, software, web, mobile, cybersecurity, and managed IT solutions that help modern businesses operate securely and grow.
