<?php
$navCurrentPage = basename($_SERVER['PHP_SELF']);

if (!function_exists('urlPath')) {
    /**
     * Build canonical internal URLs from any page depth.
     *
     * The site is deployed from public_html, so internal links should be root-based.
     * This prevents navbar links from resolving as /services/index.php,
     * /solutions/index.php, or /index.php when the visitor is on a nested clean URL.
     */
    function urlPath($path)
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '/';
        }

        if (
            strpos($path, 'http://') === 0 ||
            strpos($path, 'https://') === 0 ||
            strpos($path, 'mailto:') === 0 ||
            strpos($path, 'tel:') === 0 ||
            strpos($path, '#') === 0
        ) {
            return $path;
        }

        $normalized = ltrim($path, '/');

        // Never expose /index.php from the navbar or shared links.
        if ($normalized === 'index.php') {
            return '/';
        }

        return '/' . $normalized;
    }
}

function activeClass($pages)
{
    global $navCurrentPage;

    if (is_array($pages)) {
        return in_array($navCurrentPage, $pages, true) ? ' active' : '';
    }

    return $navCurrentPage === $pages ? ' active' : '';
}

$services = [
    [
        'label' => 'Web Development services',
        'href' => 'services/Web%20Development%20service-details.php',
        'page' => 'Web Development service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Software Development services',
        'href' => 'services/Software%20Development%20service-details.php',
        'page' => 'Software Development service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Managed Cloud Hosting',
        'href' => 'services/Managed_Cloud_Hosting.php',
        'page' => 'Managed_Cloud_Hosting.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'S.E.O services',
        'href' => 'services/S.E.O%20service-details.php',
        'page' => 'S.E.O service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Data Analytics services',
        'href' => 'services/Data%20Analytics%20service-details.php',
        'page' => 'Data Analytics service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Digital Marketing services',
        'href' => 'services/Digital%20Marketing%20service-details.php',
        'page' => 'Digital Marketing service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Website Optimization services',
        'href' => 'services/Website%20Optimization%20service-details.php',
        'page' => 'Website Optimization service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Mobile Development services',
        'href' => 'services/Mobile%20Development%20service-details.php',
        'page' => 'Mobile Development service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Wordpress Development services',
        'href' => 'services/Wordpress%20Development%20service-details.php',
        'page' => 'Wordpress Development service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'SAP Consulting services',
        'href' => 'services/SAP%20Consulting%20service-details.php',
        'page' => 'SAP Consulting service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'IT Consulting services',
        'href' => 'services/IT%20Consulting%20service-details.php',
        'page' => 'IT Consulting service-details.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'AI Chatbot Development',
        'href' => 'services/AI_Chatbot_Development.php',
        'page' => 'AI_Chatbot_Development.php',
        'class' => 'dropdown-item',
    ],
];

$servicePages = array_column($services, 'page');

$solutionLinks = [
    ['label' => 'Cloud Computing for Small Businesses', 'href' => 'solutions/cloud-computing-small-business'],
    ['label' => 'AI Automation for Small Businesses', 'href' => 'solutions/ai-automation-small-business'],
    ['label' => 'Custom PHP & MySQL Websites', 'href' => 'solutions/custom-php-mysql-website-development'],
    ['label' => 'Mobile App Development', 'href' => 'solutions/mobile-app-development-business-websites'],
    ['label' => 'Business Website SEO', 'href' => 'solutions/business-website-seo-optimization'],
    ['label' => 'Managed IT Services', 'href' => 'solutions/managed-it-services-small-businesses'],
    ['label' => 'Small Business Cloud Migration', 'href' => 'solutions/small-business-cloud-migration'],
    ['label' => 'Houston Cloud Consulting', 'href' => 'solutions/houston-cloud-consulting-services'],
    ['label' => 'Houston Web Development', 'href' => 'solutions/houston-web-development-services'],
    ['label' => 'AI Chatbot Development', 'href' => 'solutions/ai-chatbot-development-business-websites'],
    ['label' => 'Cloud Technology Services', 'href' => 'solutions/cloud-technology-services'],
    ['label' => 'Cloud-Based Computing Services', 'href' => 'solutions/cloud-based-computing-services'],
    ['label' => 'Enterprise Hybrid Cloud', 'href' => 'solutions/enterprise-hybrid-cloud'],
    ['label' => 'AWS Cloud Services', 'href' => 'solutions/aws-cloud-services'],
    ['label' => 'Azure Cloud Services', 'href' => 'solutions/microsoft-azure-cloud-services'],
    ['label' => 'Cloud Security Services', 'href' => 'solutions/cloud-security-services'],
    ['label' => 'Managed Cloud Services', 'href' => 'solutions/managed-cloud-services-provider'],
];

$projects = [
     [
        'label' => 'CEO',
        'href' => 'https://arzugil.com/',
        'class' => 'dropdown-item',
        
        ],
        [
        'label' => 'Buy Services',
        'href' => 'https://www.cloudcomputeai.com/',
        'class' => 'dropdown-item',
        
        ], 
    [
        'label' => 'Github',
        'href' => 'https://github.com/Cloud-Technology-Computing-Corporation',
        'class' => 'nav-item nav-link',
    ],
    [
        'label' => "Android App's",
        'href' => 'https://play.google.com/store/search?q=Jhon%20Arzu&c=apps&hl=en_US&gl=US',
        'class' => 'nav-item nav-link',
    ],
    [
        'label' => 'Certifications',
        'href' => 'https://www.credly.com/users/jhongil',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'LinkedIn',
        'href' => 'https://www.linkedin.com/in/jhongil/',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Portfolio Site',
        'href' => 'https://www.hybridclouddeveloper.com',
        'class' => 'dropdown-item',
    ],
   
];

$buyServices = [
    [
        'label' => 'AI Chatbot Integration',
        'href' => 'https://buy.stripe.com/4gweWe8jzdLteqcbIK',
        'class' => 'nav-item nav-link',
    ],
    [
        'label' => 'Buy Services',
        'href' => 'https://www.cloudcomputeai.com/',
        'class' => 'nav-item nav-link',
    ],
    [
        'label' => 'Custom Business Website',
        'href' => 'https://buy.stripe.com/6oEcO657n5eX4PC5kl',
        'class' => 'nav-item nav-link',
    ],
    [
        'label' => 'Monthly SEO Optimization',
        'href' => 'https://buy.stripe.com/14k4hA6br7n5eqc5kn',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Business Consulting services',
        'href' => 'https://buy.stripe.com/9AQ01k6brgXF0zm5ko',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Buy E-Book',
        'href' => 'https://www.amazon.com/dp/B0FKYLR78F?asin=B0FKYLR78F&revisionId=541f7b3e&format=3&depth=1',
        'class' => 'dropdown-item',
    ],
];

$aboutLinks = [
    [
        'label' => 'Case Studies',
        'href' => 'case-study-standard.php',
        'page' => 'case-study-standard.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Certifications',
        'href' => 'Certified.php',
        'page' => 'Certified.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Resume',
        'href' => 'https://cloudtechnologycomputing.com/JhonGil_Resume.pdf',
        'page' => '',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Our Team',
        'href' => 'team.php',
        'page' => 'team.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Pricing',
        'href' => 'pricing.php',
        'page' => 'pricing.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => "FAQ's",
        'href' => 'faq.php',
        'page' => 'faq.php',
        'class' => 'dropdown-item',
    ],
    [
        'label' => 'Contact Us!',
        'href' => 'contact.php',
        'page' => 'contact.php',
        'class' => 'dropdown-item',
    ],
    
    
];

$aboutPages = array_filter(array_column($aboutLinks, 'page'));
?>

<!-- Start header section -->
<header class="header-area2 style-2 two">
    <div class="header-logo">
        <a href="/">
            <p style="color: white;">Cloud Technology Computing</p>
        </a>
    </div>

    <div class="main-menu">
        <div class="mobile-logo-area d-lg-none d-flex justify-content-between align-items-center">
            <div class="mobile-logo-wrap">
                <a href="/">
                    <p style="color: white;">Cloud Technology Computing</p>
                </a>
            </div>
        </div>

        <ul class="menu-list">
            <li class="menu-item<?= activeClass('index.php'); ?>">
                <a href="/">Home</a>
               
            </li>
        

        <!--    <li>
             //   <a href="https://arzugil.com/" target="_blank" rel="noopener">C.E.O</a>
            </li>  -->

            <li class="menu-item-has-children<?= activeClass(array_merge(['services.php'], $servicePages)); ?>">
                <a href="<?= urlPath('services.php'); ?>" class="drop-down">Services</a>
                <i class="bi bi-plus dropdown-icon"></i>

                <ul class="sub-menu">
                    <?php foreach ($services as $service): ?>
                        <li>
                            <a href="<?= urlPath($service['href']); ?>" class="<?= $service['class']; ?><?= activeClass($service['page']); ?>">
                                <?= htmlspecialchars($service['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>


            <li class="menu-item-has-children<?= activeClass('solution.php'); ?>">
                <a href="<?= urlPath('solutions/cloud-computing-small-business'); ?>" class="drop-down">Solutions</a>
                <i class="bi bi-plus dropdown-icon"></i>
                <ul class="sub-menu">
                    <?php foreach ($solutionLinks as $solutionLink): ?>
                        <li>
                            <a href="<?= urlPath($solutionLink['href']); ?>" class="dropdown-item">
                                <?= htmlspecialchars($solutionLink['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

            <li class="menu-item-has-children<?= activeClass(['project.php', 'project-details.php', 'project-masonary.php']); ?>">
                <a href="<?= urlPath('project.php'); ?>" class="drop-down">Projects</a>
                <i class="bi bi-plus dropdown-icon"></i>

                <ul class="sub-menu">
                    <?php foreach ($projects as $project): ?>
                        <li>
                            <a href="<?= $project['href']; ?>" target="_blank" rel="noopener" class="<?= $project['class']; ?>">
                                <?= htmlspecialchars($project['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

            <li class="menu-item-has-children">
                <a href="<?= urlPath('https://www.cloudcomputeai.com/'); ?>" class="drop-down">
                    Buy Services
                </a>
                <i class="bi bi-plus dropdown-icon"></i>

                <ul class="sub-menu">
                    <?php foreach ($buyServices as $item): ?>
                        <li>
                            <a href="<?= $item['href']; ?>" target="_blank" rel="noopener" class="<?= $item['class']; ?>">
                                <?= htmlspecialchars($item['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

            <li class="menu-item<?= activeClass(['blog.php', 'blog-details.php']); ?>">
                <a href="<?= urlPath('blog.php'); ?>">Blog</a>
            </li>

            <li class="menu-item-has-children<?= activeClass(array_merge(['about.php'], $aboutPages)); ?>">
                <a href="<?= urlPath('about.php'); ?>" class="drop-down">About Us</a>
                <i class="bi bi-plus dropdown-icon"></i>

                <ul class="sub-menu">
                    <?php foreach ($aboutLinks as $link): ?>
                        <li>
                            <a href="<?= urlPath($link['href']); ?>" class="<?= $link['class']; ?><?= !empty($link['page']) ? activeClass($link['page']) : ''; ?>">
                                <?= htmlspecialchars($link['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>

        <div class="d-lg-none d-block">
            <form class="mobile-menu-form">
                <div class="hotline pt-30">
                    <div class="hotline-icon">
                        <!-- Paste your same phone SVG here -->
                          <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20.5488 16.106C20.0165 15.5518 19.3745 15.2554 18.694 15.2554C18.0191 15.2554 17.3716 15.5463 16.8173 16.1005L15.0833 17.8291C14.9406 17.7522 14.7979 17.6809 14.6608 17.6096C14.4632 17.5108 14.2766 17.4175 14.1175 17.3187C12.4932 16.2871 11.0171 14.9426 9.6013 13.2031C8.91536 12.3361 8.45441 11.6063 8.11968 10.8655C8.56965 10.4539 8.9867 10.0259 9.39277 9.61431C9.54642 9.46066 9.70007 9.30152 9.85372 9.14787C11.0061 7.9955 11.0061 6.50291 9.85372 5.35054L8.35564 3.85246C8.18553 3.68234 8.00993 3.50674 7.8453 3.33115C7.51606 2.99092 7.17034 2.63972 6.81366 2.31047C6.28137 1.78368 5.64483 1.50381 4.97535 1.50381C4.30588 1.50381 3.65836 1.78368 3.10961 2.31047C3.10412 2.31596 3.10412 2.31596 3.09864 2.32145L1.23289 4.20365C0.530497 4.90605 0.129911 5.7621 0.0421114 6.75533C-0.089588 8.35768 0.382335 9.85027 0.744508 10.827C1.63348 13.2251 2.96145 15.4475 4.94243 17.8291C7.34594 20.699 10.2378 22.9653 13.5413 24.5622C14.8034 25.1603 16.4881 25.8682 18.3703 25.9889C18.4855 25.9944 18.6062 25.9999 18.716 25.9999C19.9836 25.9999 21.0482 25.5445 21.8823 24.639C21.8878 24.628 21.8987 24.6226 21.9042 24.6116C22.1896 24.2659 22.5188 23.9531 22.8645 23.6184C23.1005 23.3934 23.3419 23.1574 23.5779 22.9105C24.1212 22.3453 24.4065 21.6868 24.4065 21.0118C24.4065 20.3314 24.1157 19.6783 23.5614 19.1296L20.5488 16.106ZM22.5133 21.8843C22.5078 21.8843 22.5078 21.8898 22.5133 21.8843C22.2993 22.1148 22.0798 22.3233 21.8439 22.5538C21.4872 22.894 21.125 23.2507 20.7848 23.6513C20.2305 24.2439 19.5775 24.5238 18.7215 24.5238C18.6392 24.5238 18.5514 24.5238 18.4691 24.5183C16.8393 24.414 15.3247 23.7775 14.1888 23.2342C11.0829 21.7307 8.35564 19.596 6.08931 16.8907C4.21808 14.6354 2.96694 12.5501 2.13833 10.3112C1.62799 8.94484 1.44142 7.88026 1.52373 6.87606C1.57861 6.23402 1.82554 5.70174 2.281 5.24628L4.15223 3.37504C4.42112 3.12262 4.70647 2.98543 4.98633 2.98543C5.33204 2.98543 5.6119 3.19396 5.7875 3.36956C5.79299 3.37504 5.79847 3.38053 5.80396 3.38602C6.1387 3.69881 6.45697 4.02257 6.79171 4.36828C6.96182 4.54388 7.13742 4.71948 7.31302 4.90056L8.8111 6.39865C9.39277 6.98032 9.39277 7.51809 8.8111 8.09976C8.65196 8.2589 8.49831 8.41804 8.33918 8.57169C7.87823 9.04361 7.43923 9.48261 6.96182 9.91063C6.95085 9.92161 6.93987 9.92709 6.93438 9.93807C6.46246 10.41 6.55026 10.8709 6.64903 11.1837C6.65452 11.2002 6.66001 11.2167 6.6655 11.2331C7.05511 12.177 7.60385 13.0659 8.43795 14.125L8.44344 14.1305C9.95798 15.9962 11.5548 17.4504 13.3163 18.5644C13.5413 18.7071 13.7718 18.8223 13.9913 18.932C14.1888 19.0308 14.3754 19.1241 14.5345 19.2229C14.5565 19.2339 14.5784 19.2503 14.6004 19.2613C14.787 19.3546 14.9626 19.3985 15.1436 19.3985C15.5991 19.3985 15.8845 19.1131 15.9777 19.0198L17.8545 17.1431C18.041 16.9566 18.3374 16.7316 18.6831 16.7316C19.0233 16.7316 19.3032 16.9456 19.4733 17.1322C19.4788 17.1376 19.4788 17.1376 19.4842 17.1431L22.5078 20.1667C23.0731 20.7265 23.0731 21.3026 22.5133 21.8843Z">
                                </path>
                                <path
                                    d="M14.0512 6.18495C15.4889 6.4264 16.7949 7.10685 17.8375 8.14947C18.8802 9.19209 19.5551 10.4981 19.8021 11.9358C19.8624 12.298 20.1752 12.5504 20.5319 12.5504C20.5758 12.5504 20.6142 12.5449 20.6581 12.5395C21.0642 12.4736 21.3331 12.0895 21.2672 11.6834C20.9709 9.94387 20.1478 8.35799 18.8911 7.10136C17.6345 5.84473 16.0486 5.0216 14.3091 4.72528C13.903 4.65943 13.5244 4.92832 13.4531 5.3289C13.3817 5.72949 13.6451 6.1191 14.0512 6.18495Z">
                                </path>
                                <path
                                    d="M25.9707 11.4691C25.4823 8.60468 24.1324 5.99813 22.0581 3.92387C19.9838 1.8496 17.3773 0.49968 14.5128 0.011294C14.1122 -0.0600432 13.7336 0.214331 13.6623 0.614917C13.5964 1.02099 13.8653 1.39963 14.2714 1.47096C16.8285 1.90447 19.1607 3.11721 21.0155 4.96649C22.8702 6.82125 24.0775 9.15343 24.511 11.7106C24.5714 12.0728 24.8841 12.3252 25.2408 12.3252C25.2847 12.3252 25.3231 12.3197 25.367 12.3142C25.7676 12.2539 26.042 11.8697 25.9707 11.4691Z">
                                </path>
                            </svg>
                    </div>

                    <div class="hotline-info">
                        <span>Call Us Now</span>
                        <h2><a href="tel:17138709966">1-713-870-9966</a></h2>
                    </div>
                </div>

                <div class="email pt-20 d-flex align-items-center">
                    <div class="email-icon">
                        <!-- Paste your same email SVG here -->
                        <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_461_205)">
                                    <path
                                        d="M23.5117 3.30075H2.38674C1.04261 3.30075 -0.0507812 4.39414 -0.0507812 5.73827V20.3633C-0.0507812 21.7074 1.04261 22.8008 2.38674 22.8008H23.5117C24.8558 22.8008 25.9492 21.7074 25.9492 20.3633V5.73827C25.9492 4.39414 24.8558 3.30075 23.5117 3.30075ZM23.5117 4.92574C23.6221 4.92574 23.7271 4.94865 23.8231 4.98865L12.9492 14.4131L2.07526 4.98865C2.17127 4.9487 2.27629 4.92574 2.38668 4.92574H23.5117ZM23.5117 21.1757H2.38674C1.93844 21.1757 1.57421 20.8116 1.57421 20.3632V6.70547L12.4168 16.1024C12.57 16.2349 12.7596 16.3008 12.9492 16.3008C13.1388 16.3008 13.3285 16.2349 13.4816 16.1024L24.3242 6.70547V20.3633C24.3242 20.8116 23.96 21.1757 23.5117 21.1757Z">
                                    </path>
                                </g>
                            </svg>
                    </div>

                    <div class="email-info">
                        <span>Email Now</span>
                        <h6><a href="mailto:Jgil20@me.com">Jgil20@me.com</a></h6>
                    </div>
                </div>
            </form>

            <div class="header-btn5">
                <a class="primary-btn3" href="<?= urlPath('form.php'); ?>">Free Consultation!!</a>
            </div>
        </div>
        
    </div>

    <div class="nav-right d-flex jsutify-content-end align-items-center">
        <div class="header-contact d-xl-block d-none">
            <span>
                <img 
                    loading="lazy" 
                    src="<?= urlPath('assets/img/home-6/phone.svg'); ?>" 
                    alt="Cloud Technology Computing: Superior client support in computer clouds for enhanced reliability and innovative technical solutions" 
                    width="30" 
                    height="10"
                >
                For Client Support:
            </span>

            <h6><a href="tel:17138709966">1-713-870-9966</a></h6>
        </div>

        <div class="header-btn d-sm-flex d-none">
            <a href="<?= urlPath('form.php'); ?>">Free Consultation!</a>
        </div>

        <div class="sidebar-button mobile-menu-btn ">
            <span></span>
        </div>
        
    </div>
    <paypal-cart-button data-id="pp-view-cart"></paypal-cart-button>
<script>
  window.addEventListener('load', function () {
    if (window.cartPaypal && typeof window.cartPaypal.Cart === 'function') {
      window.cartPaypal.Cart({ id: "pp-view-cart" });
    }
  });
</script>
</header>