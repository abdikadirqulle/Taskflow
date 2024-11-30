<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Boost Your Productivity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
   <link rel="shortcut icon" href="assets/faviconIco.png" type="image/x-icon">
   <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 7%;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1D4ED8;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            /* color: #64748b; */
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #1D4ED8;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-outline {
            border: 1px solid #1D4ED8;
            color: #1D4ED8;
        }

        .btn-primary {
            background: #1D4ED8;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(29, 78, 216, 0.1);
        }

        .hero {
            padding: 6rem 7%;
            display: flex;
            align-items: center;
            gap: 4rem;
        }

        .hero-content {
            flex: 1;
        }

        .hero-image {
            flex: 1;
        }

        .hero-image img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(29, 78, 216, 0.1);
        }

        h1 {
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #1D4ED8, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .features {
            padding: 4rem 7%;
            background: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            padding: 2rem;
            border-radius: 12px;
            background: #f8fafc;
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(29, 78, 216, 0.1);
        }

        .feature-icon {
            font-size: 2rem;
            color: #1D4ED8;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #1e293b;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.6;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #1e293b;
        }

        .pricing {
            padding: 4rem 7%;
            background: #f8fafc;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .pricing-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(29, 78, 216, 0.1);
        }

        .pricing-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .price {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1D4ED8;
            margin-bottom: 1.5rem;
        }

        .price span {
            font-size: 1rem;
            color: #64748b;
        }

        .features-list {
            list-style: none;
            margin: 1.5rem 0;
        }

        .features-list li {
            padding: 0.5rem 0;
            color: #64748b;
        }

        .testimonials {
            padding: 4rem 7%;
            background: white;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .testimonial-card {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 12px;
            position: relative;
        }

        .testimonial-content {
            margin-bottom: 1.5rem;
            color: #64748b;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .author-info h4 {
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .author-info p {
            color: #64748b;
            font-size: 0.875rem;
        }

        .contact {
            padding: 4rem 7%;
            background: #f8fafc;
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1e293b;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        footer {
            background: #1e293b;
            color: white;
            padding: 4rem 7% 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .footer-col h4 {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 0.75rem;
        }

        .footer-col ul a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-col ul a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #334155;
            color: #cbd5e1;
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                padding: 4rem 7%;
                text-align: center;
            }

            h1 {
                font-size: 2.5rem;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="#" class="logo">
            <i class="fas fa-tasks"></i>
            TaskFlow
        </a>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#pricing">Pricing</a>
            <a href="#about">About</a>
            <div class="auth-buttons">
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="register.php" class="btn btn-primary">Get Started</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>Boost Your Productivity, Simplify Your Life</h1>
            <p>Stay organized and boost your productivity with TaskFlow's intuitive task management system. Perfect for individuals and teams looking to streamline their workflow.</p>
            <a href="register.php" class="btn btn-primary">Start Free Trial</a>
        </div>
        <div class="hero-image">
            <img src="assets/hero-image.jpeg" alt="TaskFlow Dashboard">
        </div>
    </section>

    <section class="features" id="features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-list-check"></i>
                </div>
                <h3>Task Management</h3>
                <p>Organize tasks with priorities, due dates, and categories. Stay on top of your work with our intuitive interface.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Progress Tracking</h3>
                <p>Monitor your progress with visual charts and statistics. Understand your productivity patterns.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3>Smart Reminders</h3>
                <p>Never miss a deadline with customizable notifications and reminders for important tasks.</p>
            </div>
        </div>
    </section>

    <section class="pricing" id="pricing">
        <h2 class="section-title">Choose Your Plan</h2>
        <div class="pricing-grid">
            <div class="pricing-card">
                <h3>Free</h3>
                <div class="price">$0<span>/month</span></div>
                <ul class="features-list">
                    <li><i class="fas fa-check"></i> Up to 10 tasks</li>
                    <li><i class="fas fa-check"></i> Basic task management</li>
                    <li><i class="fas fa-check"></i> Email support</li>
                </ul>
                <a href="register.php" class="btn btn-outline">Get Started</a>
            </div>
            <div class="pricing-card">
                <h3>Pro</h3>
                <div class="price">$9<span>/month</span></div>
                <ul class="features-list">
                    <li><i class="fas fa-check"></i> Unlimited tasks</li>
                    <li><i class="fas fa-check"></i> Advanced analytics</li>
                    <li><i class="fas fa-check"></i> Priority support</li>
                    <li><i class="fas fa-check"></i> Team collaboration</li>
                </ul>
                <a href="register.php" class="btn btn-primary">Get Started</a>
            </div>
            <div class="pricing-card">
                <h3>Enterprise</h3>
                <div class="price">$29<span>/month</span></div>
                <ul class="features-list">
                    <li><i class="fas fa-check"></i> Everything in Pro</li>
                    <li><i class="fas fa-check"></i> Custom integrations</li>
                    <li><i class="fas fa-check"></i> 24/7 support</li>
                    <li><i class="fas fa-check"></i> Advanced security</li>
                </ul>
                <a href="register.php" class="btn btn-outline">Contact Sales</a>
            </div>
        </div>
    </section>

    <section class="testimonials" id="testimonials">
        <h2 class="section-title">What Our Users Say</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    "TaskFlow has completely transformed how I manage my daily tasks. The interface is intuitive and the features are exactly what I needed."
                </div>
                <div class="testimonial-author">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=1D4ED8&color=fff" alt="Sarah Johnson" class="author-avatar">
                    <div class="author-info">
                        <h4>Sarah Johnson</h4>
                        <p>Freelance Designer</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-content">
                    "As a team leader, TaskFlow helps me keep track of everyone's progress. The collaboration features are outstanding!"
                </div>
                <div class="testimonial-author">
                    <img src="https://ui-avatars.com/api/?name=Michael+Chen&background=1D4ED8&color=fff" alt="Michael Chen" class="author-avatar">
                    <div class="author-info">
                        <h4>Michael Chen</h4>
                        <p>Project Manager</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-content">
                    "The productivity boost I've experienced with TaskFlow is incredible. It's worth every penny!"
                </div>
                <div class="testimonial-author">
                    <img src="https://ui-avatars.com/api/?name=Emily+Davis&background=1D4ED8&color=fff" alt="Emily Davis" class="author-avatar">
                    <div class="author-info">
                        <h4>Emily Davis</h4>
                        <p>Small Business Owner</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <h2 class="section-title">Get in Touch</h2>
        <form class="contact-form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Product</h4>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Press</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Resources</h4>
                <ul>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Connect</h4>
                <ul>
                    <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                    <li><a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
                    <li><a href="#"><i class="fab fa-github"></i> GitHub</a></li>
                    <li><a href="#"><i class="fab fa-discord"></i> Discord</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 TaskFlow. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
