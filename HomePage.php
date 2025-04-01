<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G&C Creative Technology</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <!-- Banner and Navigation -->
    <div id="home" class="con">
        <div class="banner">
            <div class="nav">
                <div class="logo" data-aos="fade-right">
                    <img src="assets/images/logo.jpg" alt="G&C Creative Technology Logo">
                </div>
                <ul data-aos="fade-down">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#service">Services</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#faqs">FAQs</a></li>
                </ul>
                <button data-aos="fade-left">
                    <a href="CheckPage.php">Login</a>
                </button>
            </div>
            <div class="content" data-aos="zoom-in">
                <h1>Welcome To G&C Creative Technology</h1>
                <p class="mt-4 fs-5">Innovative Solutions for Tomorrow's Challenges</p>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div id="service" class="container-fluid py-5">
        <h1 class="section-title" data-aos="fade-down">Our Services</h1>
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="card h-100 text-center p-4 hover-scale">
                        <div class="mb-4">
                            <i class="bi bi-code-slash display-4 text-primary"></i>
                        </div>
                        <h3>Software Development</h3>
                        <p class="text-muted">Custom software solutions tailored to your business needs</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 text-center p-4 hover-scale">
                        <div class="mb-4">
                            <i class="bi bi-cloud display-4 text-primary"></i>
                        </div>
                        <h3>Cloud Computing</h3>
                        <p class="text-muted">Scalable cloud solutions for modern businesses</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 text-center p-4 hover-scale">
                        <div class="mb-4">
                            <i class="bi bi-database display-4 text-primary"></i>
                        </div>
                        <h3>Database Management</h3>
                        <p class="text-muted">Efficient data management and optimization</p>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="card h-100 text-center p-4 hover-scale">
                        <div class="mb-4">
                            <i class="bi bi-shield-check display-4 text-primary"></i>
                        </div>
                        <h3>Cyber Security</h3>
                        <p class="text-muted">Protecting your digital assets from threats</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 text-center p-4 hover-scale">
                        <div class="mb-4">
                            <i class="bi bi-graph-up display-4 text-primary"></i>
                        </div>
                        <h3>Digital Marketing</h3>
                        <p class="text-muted">Strategic online presence and growth</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 text-center p-4 hover-scale">
                        <div class="mb-4">
                            <i class="bi bi-hdd-network display-4 text-primary"></i>
                        </div>
                        <h3>Network Solutions</h3>
                        <p class="text-muted">Reliable networking infrastructure</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div id="about" class="container-fluid py-5 bg-light">
        <div class="container">
            <h1 class="section-title" data-aos="fade-down">About Us</h1>
            <div class="row align-items-center">
                <div class="col-md-6" data-aos="fade-right">
                    <img src="assets/images/about.jpg" alt="About Us" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <h2 class="mb-4">Your Technology Partner</h2>
                    <p class="lead mb-4">G&C Creative Technology is dedicated to delivering innovative solutions that drive business growth and efficiency.</p>
                    <p class="text-muted">With years of experience in the technology sector, we understand the challenges businesses face in the digital age. Our team of experts works tirelessly to provide cutting-edge solutions that help our clients stay ahead of the competition.</p>
                    <div class="mt-4">
                        <h4 class="mb-3">Why Choose Us?</h4>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            <span>Expert Team</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            <span>Quality Service</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            <span>24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQs Section -->
    <div id="faqs" class="container-fluid py-5">
        <div class="container">
            <h1 class="section-title" data-aos="fade-down">Frequently Asked Questions</h1>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item" data-aos="fade-up">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            What services do you offer?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We offer a comprehensive range of technology services including software development, cloud computing, cybersecurity, digital marketing, and network solutions.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            How can I get started with your services?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Getting started is easy! Simply contact us through our website or give us a call. We'll schedule a consultation to understand your needs and create a tailored solution.
                        </div>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Do you provide ongoing support?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we provide 24/7 support for all our services. Our dedicated support team is always ready to help you with any issues or questions you may have.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>G&C Creative Technology</h5>
                    <p class="mb-0">Innovative Solutions for Tomorrow's Challenges</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2025 G&C Creative Technology. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>