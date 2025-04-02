<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\User; 

class StripePaymentController extends Controller
{
    public function redirectToStripe()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Create a new Checkout Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Subscription',
                        ],
                        'unit_amount' => 54, // Amount in cents
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('payment.complete').'?success=true',
            'cancel_url' => route('payment.complete').'?success=false',
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session', // Saves for future charges
            ]
        ]);

        return redirect()->away($session->url);
    }

    public function handleCheckoutCompleted(Request $request)
    {
        if ($request->query('success')) {
            
            $user = \Session::get('registered_user');
            $user->status = 1; // Change status to whatever you need, e.g., 1 for active
            $user->save();
    
            $to = $user->email; // Assuming $user is the user model object
            $subject = 'Login Credentials';
            $message = "Hello " . $user->name . ",\n\n";
            $message .= "Your login credentials:\n";
            $message .= "Email: " . $user->email . "\n";
            $message .= "Password: " . $user->password_text . "\n\n";
            $message .= "Please keep your login credentials secure and do not share them with anyone.\n\n";
            $message .= "Best regards,\nYour Application";
            
            if (mail($to, $subject, $message)) {
                //return redirect(route('login'))->with('success', 'Credentials have been sent on your mail');
                return redirect('/app_index')->with('success', 'Credentials have been sent on your mail');
            } else {
                echo "Failed to send email.";
            }

            die;
        } else {
            // Payment failed or canceled
            // Redirect to failure page or handle error accordingly
        }
    }
}