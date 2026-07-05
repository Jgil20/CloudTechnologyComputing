<?php
/**
 * HTML Sitemap — comprehensive list of all public URLs.
 * Designed to help both users and search engines discover content.
 */
$pageTitle = 'Sitemap | Cloud Technology Computing';
$pageDescription = 'Complete sitemap of Cloud Technology Computing — cloud, AI, web, mobile, SEO, and managed IT services for small businesses in Houston and beyond.';
$canonicalUrl = 'https://www.cloudtechnologycomputing.com/sitemap';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
<meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>" />
<meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>" />
<meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>" />
<meta property="og:url" content="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="Cloud Technology Computing" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>" />
<meta name="twitter:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>" />

<!-- Sitemap structured data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "@id": "https://www.cloudtechnologycomputing.com/#website",
    "url": "https://www.cloudtechnologycomputing.com/",
    "name": "Cloud Technology Computing",
    "inLanguage": "en-US",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "https://www.cloudtechnologycomputing.com/blog.php?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://www.cloudtechnologycomputing.com/"},
        {"@type": "ListItem", "position": 2, "name": "Sitemap", "item": "https://www.cloudtechnologycomputing.com/sitemap"}
    ]
}
</script>

<?php
// Try to inject the same CSS that header.php does (lightweight)
$css = [
    '/style.min.css',
    '/css/seo-engagement.min.css',
    '/assets/css/bootstrap.min.css',
];
foreach ($css as $c): ?>
<link rel="stylesheet" href="<?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8'); ?>" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="<?= htmlspecialchars($c, ENT_QUOTES, 'UTF-8'); ?>"></noscript>
<?php endforeach; ?>

<style>
  body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0b1220; color: #d6deeb; margin: 0; padding: 0; }
  .sitemap-wrap { max-width: 1200px; margin: 0 auto; padding: 2rem 1.5rem; }
  .sitemap-hero { padding: 2.5rem 0 1.5rem; border-bottom: 1px solid rgba(148,163,184,.2); margin-bottom: 2rem; }
  .sitemap-hero h1 { color: #fff; font-size: 2.25rem; margin: 0 0 .5rem; }
  .sitemap-hero p { color: #94a3b8; font-size: 1.05rem; margin: 0; }
  .sitemap-breadcrumb { font-size: .9rem; color: #94a3b8; margin-bottom: 1rem; }
  .sitemap-breadcrumb a { color: #93c5fd; text-decoration: none; }
  .sitemap-section { margin: 2rem 0; }
  .sitemap-section h2 { color: #fff; font-size: 1.4rem; margin: 0 0 .75rem; padding-bottom: .4rem; border-bottom: 1px solid rgba(148,163,184,.15); }
  .sitemap-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: .65rem 1.5rem; list-style: none; padding: 0; margin: 0; }
  .sitemap-grid li a { color: #93c5fd; text-decoration: none; display: block; padding: .35rem 0; line-height: 1.4; font-size: .95rem; }
  .sitemap-grid li a:hover { color: #bfdbfe; text-decoration: underline; }
  .sitemap-grid li small { display: block; color: #94a3b8; font-size: .8rem; margin-top: .15rem; }
  .sitemap-stats { background: rgba(15,23,42,.5); border: 1px solid rgba(148,163,184,.2); border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1.5rem; }
  .sitemap-stats div { color: #cbd5e1; font-size: .9rem; }
  .sitemap-stats strong { color: #fff; display: block; font-size: 1.5rem; font-weight: 600; }
  .sitemap-back-to-top { margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(148,163,184,.15); text-align: center; }
  .sitemap-back-to-top a { color: #93c5fd; text-decoration: none; }
</style>
</head>
<body>

<div class="sitemap-wrap" id="top">

    <div class="sitemap-breadcrumb">
        <a href="/">Home</a> &rsaquo; Sitemap
    </div>

    <header class="sitemap-hero">
        <h1>HTML Sitemap</h1>
        <p>Every public page on Cloud Technology Computing — for people and search engines.</p>
    </header>

    <?php
    // Count and group all URLs
    $sections = [
        'Main Pages' => [
            ['/'                       , 'Homepage', 'Cloud, AI, web, and managed IT services for small businesses.'],
            ['/about.php'              , 'About', 'Learn about Cloud Technology Computing and founder Jhon Arzu-Gil.'],
            ['/services.php'           , 'Services', 'Cloud, AI, SEO, web, and mobile development services.'],
            ['/pricing.php'            , 'Pricing', 'Transparent pricing for cloud, web, and managed services.'],
            ['/contact.php'            , 'Contact', 'Phone, email, and consultation form.'],
            ['/form.php'               , 'Free Consultation', 'Book a free cloud and website consultation.'],
            ['/blog.php'               , 'Blog', 'Articles on cloud, AI, SEO, web, and mobile technology.'],
            ['/case-study-standard.php', 'Case Studies', 'Real-world project results.'],
            ['/project.php'            , 'Portfolio', 'Featured cloud, web, and mobile projects.'],
            ['/team.php'               , 'Team', 'Meet the Cloud Technology Computing team.'],
            ['/shop.php'               , 'Shop', 'Digital services and products for sale.'],
            ['/faq.php'                , 'FAQ', 'Frequently asked questions.'],
            ['/website-load-times.php' , 'Website Load Times', 'Performance and load-time case study.'],
        ],
        'Legal & Policy' => [
            ['/privacy-policy', 'Privacy Policy', 'How we collect, use, and protect your information.'],
            ['/terms', 'Terms and Conditions', 'Website and service terms.'],
            ['/support-policy', 'Support Policy', 'Support availability, priorities, and procedures.'],
        ],
        'Cloud Solutions' => [
            ['/solutions/cloud-computing-small-business', 'Cloud Computing for Small Business'],
            ['/solutions/cloud-computing-services-houston', 'Cloud Computing Services Houston'],
            ['/solutions/cloud-services-small-business', 'Cloud Services for Small Business'],
            ['/solutions/houston-cloud-consulting-services', 'Houston Cloud Consulting'],
            ['/solutions/cloud-computing-company-houston', 'Houston Cloud Computing Company'],
            ['/solutions/cloud-security-services', 'Cloud Security Services'],
            ['/solutions/cloud-security-services-small-businesses', 'Cloud Security for Small Business'],
            ['/solutions/cloud-technology-services', 'Cloud Technology Services'],
            ['/solutions/cloud-technology-solutions', 'Cloud Technology Solutions'],
            ['/solutions/cloud-technology-partner', 'Cloud Technology Partner'],
            ['/solutions/cloud-technology-consulting', 'Cloud Technology Consulting'],
            ['/solutions/cloud-computing-technology', 'Cloud Computing Technology'],
            ['/solutions/cloud-processing-services', 'Cloud Processing Services'],
            ['/solutions/cloud-based-computing-services', 'Cloud-Based Computing Services'],
            ['/solutions/cloud-infrastructure-services', 'Cloud Infrastructure Services'],
            ['/solutions/cloud-server-services', 'Cloud Server Services'],
            ['/solutions/managed-cloud-services-provider', 'Managed Cloud Services Provider'],
            ['/solutions/aws-cloud-services', 'AWS Cloud Services'],
            ['/solutions/microsoft-azure-cloud-services', 'Microsoft Azure Cloud Services'],
            ['/solutions/enterprise-hybrid-cloud', 'Enterprise Hybrid Cloud'],
            ['/solutions/cloud-data-security', 'Cloud Data Security'],
            ['/solutions/iaas-cloud-infrastructure', 'IaaS Cloud Infrastructure'],
            ['/solutions/saas-paas-iaas-cloud-models', 'SaaS, PaaS, IaaS Cloud Models'],
            ['/solutions/cloud-migration-companies-small-business', 'Cloud Migration for Small Business'],
            ['/solutions/cloud-it-consulting-services', 'Cloud IT Consulting'],
            ['/solutions/cloud-software-development-company', 'Cloud Software Development'],
            ['/solutions/small-business-cloud-migration', 'Small Business Cloud Migration'],
        ],
        'AI, Web, and Mobile Solutions' => [
            ['/solutions/ai-automation-small-business', 'AI Automation for Small Business'],
            ['/solutions/ai-chatbot-development-business-websites', 'AI Chatbot Development'],
            ['/solutions/custom-php-mysql-website-development', 'Custom PHP/MySQL Web Development'],
            ['/solutions/houston-web-development-services', 'Houston Web Development Services'],
            ['/solutions/mobile-app-development-business-websites', 'Mobile App Development'],
            ['/solutions/webview-app-development-ios-android', 'WebView App Development (iOS/Android)'],
            ['/solutions/business-website-seo-optimization', 'Business Website SEO'],
            ['/solutions/managed-it-services-small-businesses', 'Managed IT Services'],
            ['/solutions/it-consulting-solutions', 'IT Consulting Solutions'],
            ['/solutions/sap-consulting-services', 'SAP Consulting Services'],
        ],
        'Solution Resources' => [
            ['/solutions/outcomes', 'Outcomes'],
            ['/solutions/process', 'Our Process'],
            ['/solutions/use_cases', 'Use Cases'],
            ['/solutions/faq', 'Solutions FAQ'],
        ],
    ];

    $total = 0;
    foreach ($sections as $rows) { $total += count($rows); }
    ?>

    <div class="sitemap-stats">
        <?php foreach ($sections as $name => $rows): ?>
            <div>
                <strong><?= count($rows); ?></strong>
                <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endforeach; ?>
        <div>
            <strong><?= $total; ?></strong>
            Total Pages
        </div>
    </div>

    <?php foreach ($sections as $name => $rows): ?>
        <section class="sitemap-section">
            <h2><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h2>
            <ul class="sitemap-grid">
                <?php foreach ($rows as $r): ?>
                    <li>
                        <a href="<?= htmlspecialchars($r[0], ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars($r[1], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                        <?php if (!empty($r[2])): ?>
                            <small><?= htmlspecialchars($r[2], ENT_QUOTES, 'UTF-8'); ?></small>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endforeach; ?>

    <div class="sitemap-back-to-top">
        <p>Looking for the XML sitemap for search engines? <a href="/sitemap.xml">View XML sitemap</a></p>
        <p><a href="#top" aria-label="Back to top">&uarr; Back to top</a></p>
    </div>

</div>

</body>
</html>
