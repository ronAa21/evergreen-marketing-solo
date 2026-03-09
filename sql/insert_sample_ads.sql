-- Insert 20 Sample Advertisements for Evergreen Bank
-- These are attractive banking promotions and offers
-- Using placeholder images from picsum.photos (reliable placeholder service)

INSERT INTO advertisements (title, description, image_path, status, created_at, updated_at) VALUES
('Zero Annual Fee Credit Card', 'Get your first credit card with ZERO annual fee for life! Enjoy cashback rewards on every purchase and exclusive dining privileges. Apply now and start earning rewards today.', 'https://picsum.photos/seed/creditcard1/800/600', 'active', NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY),

('5% Cashback on Groceries', 'Shop smarter with our Grocery Rewards Card! Earn up to 5% cashback on all grocery purchases, 3% on gas, and 1% on everything else. Limited time offer - sign up today!', 'https://picsum.photos/seed/grocery2/800/600', 'active', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY),

('Home Loan at 3.5% Interest', 'Make your dream home a reality! Get pre-approved for a home loan with interest rates as low as 3.5% APR. Flexible payment terms up to 30 years. Free consultation available.', 'https://picsum.photos/seed/homeloan3/800/600', 'active', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY),

('Student Savings Account Bonus', 'Students save more! Open a student savings account and get ₱500 bonus. No maintaining balance required. Plus, enjoy free online banking and mobile app access.', 'https://picsum.photos/seed/student4/800/600', 'active', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 4 DAY),

('Travel Rewards Card - Fly Free', 'Earn 2 miles for every ₱1 spent! Get 50,000 bonus miles after your first purchase. Enjoy free airport lounge access, travel insurance, and no foreign transaction fees.', 'https://picsum.photos/seed/travel5/800/600', 'active', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 5 DAY),

('Business Loan Up to ₱5M', 'Grow your business with our flexible business loans! Get up to ₱5 million with competitive rates starting at 4.5%. Fast approval in 48 hours. Apply online now.', 'https://picsum.photos/seed/business6/800/600', 'active', NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 6 DAY),

('High-Yield Savings Account', 'Earn up to 4.5% annual interest on your savings! No monthly fees, no minimum balance. Watch your money grow with our premium savings account. Open yours today.', 'https://picsum.photos/seed/savings7/800/600', 'active', NOW() - INTERVAL 1 HOUR, NOW() - INTERVAL 1 HOUR),

('Refer a Friend - Get ₱1,000', 'Share the love and earn rewards! Refer friends to Evergreen Bank and get ₱1,000 for each successful referral. Your friend gets ₱500 too. Win-win for everyone!', 'https://picsum.photos/seed/refer8/800/600', 'active', NOW() - INTERVAL 2 HOUR, NOW() - INTERVAL 2 HOUR),

('Auto Loan Special Promo', 'Drive your dream car today! Get auto loans with rates as low as 3.9% and up to 7 years to pay. Pre-approval in minutes. New and used cars accepted.', 'https://picsum.photos/seed/autoloan9/800/600', 'active', NOW() - INTERVAL 3 HOUR, NOW() - INTERVAL 3 HOUR),

('Senior Citizen Benefits', 'Exclusive benefits for our senior citizens! Enjoy higher interest rates, priority banking, free annual credit card, and special discounts. You deserve the best.', 'https://picsum.photos/seed/senior10/800/600', 'active', NOW() - INTERVAL 4 HOUR, NOW() - INTERVAL 4 HOUR),

('Digital Banking Made Easy', 'Bank anytime, anywhere! Download our mobile app and enjoy instant transfers, bill payments, QR payments, and more. Get ₱200 bonus on your first transaction.', 'https://picsum.photos/seed/digital11/800/600', 'active', NOW() - INTERVAL 5 HOUR, NOW() - INTERVAL 5 HOUR),

('Personal Loan in 24 Hours', 'Need cash fast? Get personal loans up to ₱500,000 with approval in just 24 hours! Low interest rates, flexible terms, and minimal requirements. Apply now.', 'https://picsum.photos/seed/personal12/800/600', 'active', NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 7 DAY),

('Platinum Card Exclusive', 'Experience luxury banking with our Platinum Card. Unlimited airport lounge access worldwide, concierge service, travel insurance, and exclusive dining privileges.', 'https://picsum.photos/seed/platinum13/800/600', 'active', NOW() - INTERVAL 8 DAY, NOW() - INTERVAL 8 DAY),

('Time Deposit Special Rate', 'Lock in high returns! Get up to 5.5% interest per annum on time deposits. Minimum placement of ₱100,000. Terms from 30 days to 5 years available.', 'https://picsum.photos/seed/timedeposit14/800/600', 'active', NOW() - INTERVAL 9 DAY, NOW() - INTERVAL 9 DAY),

('Online Shopping Cashback', 'Shop online and save! Get 10% cashback on all online purchases with our e-Commerce Card. Valid at all major online retailers. Maximum ₱2,000 cashback per month.', 'https://picsum.photos/seed/shopping15/800/600', 'active', NOW() - INTERVAL 10 DAY, NOW() - INTERVAL 10 DAY),

('Insurance Protection Plan', 'Protect what matters most. Get comprehensive life and health insurance with affordable premiums. Free financial planning consultation included. Secure your family\'s future.', 'https://picsum.photos/seed/insurance16/800/600', 'active', NOW() - INTERVAL 11 DAY, NOW() - INTERVAL 11 DAY),

('Investment Opportunities', 'Grow your wealth with our investment products! Mutual funds, UITFs, and bonds available. Start with as low as ₱5,000. Expert advisors ready to help you.', 'https://picsum.photos/seed/investment17/800/600', 'active', NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 12 DAY),

('Payroll Account Benefits', 'Switch your payroll to Evergreen Bank! Get free checking account, free ATM card, free online banking, and exclusive employee loan rates. Talk to your HR today.', 'https://picsum.photos/seed/payroll18/800/600', 'active', NOW() - INTERVAL 13 DAY, NOW() - INTERVAL 13 DAY),

('Holiday Loan Promo', 'Make this holiday season special! Get instant holiday loans up to ₱200,000 with low interest rates. Perfect for travel, shopping, or home improvements. Apply today.', 'https://picsum.photos/seed/holiday19/800/600', 'active', NOW() - INTERVAL 14 DAY, NOW() - INTERVAL 14 DAY),

('Rewards Points Multiplier', 'Triple your rewards this month! Earn 3X points on all purchases. Redeem for cash, travel, shopping vouchers, or gadgets. Limited time only - don\'t miss out!', 'https://picsum.photos/seed/rewards20/800/600', 'active', NOW() - INTERVAL 15 DAY, NOW() - INTERVAL 15 DAY);
