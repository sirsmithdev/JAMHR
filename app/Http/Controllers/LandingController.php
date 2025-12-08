<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $features = [
            [
                'icon' => 'calculator',
                'title' => 'Payroll & Tax',
                'description' => 'Automatic NIS, NHT, Education Tax, and PAYE calculations. Generate payslips and submit to TAJ with confidence.',
            ],
            [
                'icon' => 'heart',
                'title' => 'Employee Benefits',
                'description' => 'Manage health insurance, pension plans, staff loans, and allowances. Track taxable benefits automatically.',
            ],
            [
                'icon' => 'calendar',
                'title' => 'Leave Management',
                'description' => 'Jamaica labor law compliant leave tracking. Vacation, sick, maternity (12 weeks), and paternity (20 days) leave.',
            ],
            [
                'icon' => 'clock',
                'title' => 'Time & Attendance',
                'description' => 'Track employee hours, overtime, and attendance. Integrate with biometric devices and mobile clock-in.',
            ],
            [
                'icon' => 'users',
                'title' => 'Hiring & Onboarding',
                'description' => 'Post jobs, track applications, schedule interviews, and onboard new hires seamlessly.',
            ],
            [
                'icon' => 'chart',
                'title' => 'Performance Management',
                'description' => 'Set goals, conduct appraisals, and track employee performance with customizable review cycles.',
            ],
            [
                'icon' => 'folder',
                'title' => 'Document Management',
                'description' => 'Store and organize employee documents securely. TRN, NIS cards, contracts, and certifications.',
            ],
            [
                'icon' => 'shield',
                'title' => 'Compliance & Reporting',
                'description' => 'Generate SO 2 forms, statutory reports, and stay compliant with Jamaica labor laws.',
            ],
        ];

        $pricingPlans = [
            [
                'name' => 'Starter',
                'price' => 2500,
                'period' => 'per employee/month',
                'description' => 'Perfect for small businesses getting started',
                'features' => [
                    'Up to 25 employees',
                    'Payroll & Tax calculations',
                    'Leave management',
                    'Employee directory',
                    'Basic reporting',
                    'Email support',
                ],
                'cta' => 'Start Free Trial',
                'highlighted' => false,
            ],
            [
                'name' => 'Professional',
                'price' => 4500,
                'period' => 'per employee/month',
                'description' => 'For growing businesses that need more',
                'features' => [
                    'Up to 100 employees',
                    'Everything in Starter',
                    'Benefits management',
                    'Staff loans & allowances',
                    'Performance reviews',
                    'Time & attendance',
                    'Document management',
                    'Priority support',
                ],
                'cta' => 'Start Free Trial',
                'highlighted' => true,
            ],
            [
                'name' => 'Enterprise',
                'price' => null,
                'period' => 'Custom pricing',
                'description' => 'For large organizations with complex needs',
                'features' => [
                    'Unlimited employees',
                    'Everything in Professional',
                    'Multi-location support',
                    'Custom integrations',
                    'API access',
                    'Dedicated account manager',
                    'On-site training',
                    'SLA guarantee',
                ],
                'cta' => 'Contact Sales',
                'highlighted' => false,
            ],
        ];

        $faqs = [
            [
                'question' => 'How does JamHR handle Jamaica tax calculations?',
                'answer' => 'JamHR automatically calculates all statutory deductions including NIS (3% employee, 3% employer), NHT (2% employee, 3% employer), Education Tax (2.25% employee, 3.5% employer), and PAYE based on current tax tables. We update our system whenever rates change.',
            ],
            [
                'question' => 'Can I import my existing employee data?',
                'answer' => 'Yes! We support bulk imports via CSV/Excel files. Our onboarding team will help you migrate your existing employee records, payroll history, and leave balances at no extra cost.',
            ],
            [
                'question' => 'Is my data secure?',
                'answer' => 'Absolutely. We use bank-level encryption (AES-256), secure data centers, and comply with international data protection standards. Your data is backed up daily and never shared with third parties.',
            ],
            [
                'question' => 'How long does implementation take?',
                'answer' => 'Most businesses are up and running within 1-2 weeks. This includes data migration, system configuration, and staff training. Larger organizations may need 3-4 weeks for full implementation.',
            ],
            [
                'question' => 'Do you offer training and support?',
                'answer' => 'Yes! All plans include email support and access to our knowledge base. Professional and Enterprise plans include priority support, video training sessions, and dedicated onboarding assistance.',
            ],
            [
                'question' => 'Can I try JamHR before committing?',
                'answer' => 'Yes, we offer a 14-day free trial with full access to all features. No credit card required. Our team will help you set up a demo environment with sample data.',
            ],
        ];

        return view('landing.index', compact('features', 'pricingPlans', 'faqs'));
    }

    public function pricing()
    {
        return view('landing.pricing');
    }

    public function features()
    {
        return view('landing.features');
    }

    public function contact()
    {
        return view('landing.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: Send email notification or store in database

        return back()->with('success', 'Thank you for your message. We\'ll get back to you within 24 hours.');
    }

    public function requestDemo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'required|string|max:255',
            'employees' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
        ]);

        // TODO: Send demo request notification

        return back()->with('success', 'Demo request received! Our team will contact you within 24 hours to schedule your personalized demo.');
    }
}
