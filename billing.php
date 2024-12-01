<?php
require_once 'config.php';

// Ensure user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
// Get user information from session
$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing & Plans | TaskFlow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="assets/faviconIco.png" type="image/x-icon">
    <style>
        .billing-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .billing-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .billing-header h1 {
            font-size: 2.5rem;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .billing-header p {
            color: #64748b;
            font-size: 1.1rem;
        }

        .plans-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .plan-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: 2px solid transparent;
        }

        .plan-card:hover {
            transform: translateY(-5px);
        }

        .plan-card.popular {
            border-color: #1d4ed8;
            position: relative;
        }

        .popular-badge {
            position: absolute;
            top: -12px;
            right: 2rem;
            background: #1d4ed8;
            color: white;
            padding: 0.25rem 1rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .plan-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1d4ed8;
            margin-bottom: 1.5rem;
        }

        .plan-price span {
            font-size: 1rem;
            color: #64748b;
            font-weight: 400;
        }

        .plan-features {
            list-style: none;
            margin-bottom: 2rem;
        }

        .plan-features li {
            color: #475569;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .plan-features i {
            color: #1d4ed8;
        }

        .select-plan-btn {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            background: #1d4ed8;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .select-plan-btn:hover {
            background: #1e40af;
        }

        .select-plan-btn.outline {
            background: transparent;
            border: 2px solid #1d4ed8;
            color: #1d4ed8;
        }

        .select-plan-btn.outline:hover {
            background: #1d4ed8;
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1100;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.active {
            display: flex;
            opacity: 1;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            width: 90%;
            max-width: 600px;
            position: relative;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: #1e293b;
        }

        .modal-header {
            margin-bottom: 2rem;
            padding-right: 2rem;
        }

        .plan-summary {
            background: #f8fafc;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .plan-summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .plan-name {
            font-weight: 600;
        }

        .plan-price {
            color: #1d4ed8;
            font-weight: 600;
        }

        .user-info-summary {
            color: #64748b;
            font-size: 0.875rem;
            border-top: 1px solid #e2e8f0;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
        }

        /* Phone input styles */
        .phone-input {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .country-code {
            background: #f1f5f9;
            padding: 0.75rem;
            border-radius: 0.5rem;
            color: #1e293b;
            font-weight: 500;
            border: 2px solid #e2e8f0;
        }

        .phone-input input {
            flex: 1;
        }

        .payment-type-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .payment-type-btn {
            flex: 1;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .payment-type-btn.active {
            border-color: #1d4ed8;
            background: #f1f5f9;
        }

        .payment-type-btn img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .payment-fields {
            display: none;
        }

        .payment-fields.active {
            display: block;
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

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1d4ed8;
        }

        .card-details {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1rem;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: #1d4ed8;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background: #1e40af;
        }

        .payment-note {
            text-align: center;
            margin-top: 1rem;
            color: #64748b;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .billing-header h1 {
                font-size: 2rem;
            }

            .plan-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="billing-container" style="margin-top: 6rem;">
        <div class="billing-header">
            <h1>Choose Your Plan</h1>
            <p>Select the perfect plan for your needs. Upgrade or downgrade at any time.</p>
        </div>

        <div class="plans-container">
            <!-- Free Plan -->
            <div class="plan-card">
                <div class="plan-name">Free</div>
                <div class="plan-price">$0 <span>/month</span></div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Up to 10 tasks</li>
                    <li><i class="fas fa-check"></i> Basic task management</li>
                    <li><i class="fas fa-check"></i> Email support</li>
                </ul>
                <button class="select-plan-btn outline">Current Plan</button>
            </div>

            <!-- Pro Plan -->
            <div class="plan-card popular">
                <div class="popular-badge">Most Popular</div>
                <div class="plan-name">Pro</div>
                <div class="plan-price">$9.99 <span>/month</span></div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Unlimited tasks</li>
                    <li><i class="fas fa-check"></i> Advanced task management</li>
                    <li><i class="fas fa-check"></i> Priority support</li>
                    <li><i class="fas fa-check"></i> Team collaboration</li>
                    <li><i class="fas fa-check"></i> Custom labels</li>
                </ul>
                <button class="select-plan-btn" onclick="upgradePlan('pro')">Upgrade Now</button>
            </div>

            <!-- Enterprise Plan -->
            <div class="plan-card">
                <div class="plan-name">Enterprise</div>
                <div class="plan-price">$24.99 <span>/month</span></div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Everything in Pro</li>
                    <li><i class="fas fa-check"></i> Advanced analytics</li>
                    <li><i class="fas fa-check"></i> API access</li>
                    <li><i class="fas fa-check"></i> 24/7 phone support</li>
                    <li><i class="fas fa-check"></i> Custom integration</li>
                </ul>
                <button class="select-plan-btn" onclick="upgradePlan('enterprise')">Upgrade Now</button>
            </div>
        </div>

        <!-- Payment Modal -->
        <div id="paymentModal" class="modal">
            <div class="modal-content">
                <button class="close-modal" onclick="closePaymentModal()">×</button>
                <div class="modal-header">
                    <h2>Payment Information</h2>
                </div>
                <div class="payment-form">
                    <!-- Plan and User Summary -->
                    <div class="plan-summary">
                        <div class="plan-summary-header">
                            <span class="plan-name" id="selectedPlan">Pro Plan</span>
                            <span class="plan-price" id="planPrice">$9.99/month</span>
                        </div>
                        <div class="user-info-summary">
                            <div>Name: <?php echo $user['name'] ?? 'name not found'; ?></div>
                            <div>Email: <?php echo $user['email'] ?? 'email not found'; ?></div>
                        </div>
                    </div>

                    <div class="payment-type-selector">
                        <button type="button" class="payment-type-btn active" onclick="switchPaymentType('mastercard')">
                            <i class="fab fa-cc-mastercard fa-2x"></i>
                            <span>Mastercard</span>
                        </button>
                        <button type="button" class="payment-type-btn" onclick="switchPaymentType('evc')">
                            <img src="assets/evc logo.jpeg" alt="EVC">
                            <span>EVC Plus</span>
                        </button>
                    </div>

                    <!-- Mastercard Payment Fields -->
                    <div id="mastercard-fields" class="payment-fields active">
                        <div class="form-group">
                            <label>Card Number</label>
                            <input type="text" maxlength="16" placeholder="1234 5678 9012 3456">
                        </div>
                        <div class="card-details">
                            <div class="form-group">
                                <label>Cardholder Name</label>
                                <input type="text" placeholder="John Doe">
                            </div>
                            <div class="form-group">
                                <label>Expiry</label>
                                <input type="text" placeholder="MM/YY" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label>CVV</label>
                                <input type="text" maxlength="3" placeholder="123">
                            </div>
                        </div>
                    </div>

                    <!-- EVC Payment Fields -->
                    <div id="evc-fields" class="payment-fields">
                        <div class="form-group">
                            <label>EVC Plus Number</label>
                            <div class="phone-input">
                                <span class="country-code">+252</span>
                                <input type="tel" placeholder="61 XXX XXXX" maxlength="9">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Account Name</label>
                            <input type="text" placeholder="Enter account name">
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Complete Payment</button>
                    <p class="payment-note">Your payment information is securely processed</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchPaymentType(type) {
            // Update active button
            document.querySelectorAll('.payment-type-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            // Show/hide payment fields
            document.querySelectorAll('.payment-fields').forEach(field => {
                field.classList.remove('active');
            });
            document.getElementById(type + '-fields').classList.add('active');
        }

        function upgradePlan(plan) {
            // Update plan information
            const planInfo = {
                'pro': {
                    name: 'Pro Plan',
                    price: '$9.99/month'
                },
                'enterprise': {
                    name: 'Enterprise Plan',
                    price: '$24.99/month'
                }
            };
            
            document.getElementById('selectedPlan').textContent = planInfo[plan].name;
            document.getElementById('planPrice').textContent = planInfo[plan].price;
            
            // Show payment modal
            document.getElementById('paymentModal').classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Close modal when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closePaymentModal();
            }
        });
    </script>
</body>
</html>
