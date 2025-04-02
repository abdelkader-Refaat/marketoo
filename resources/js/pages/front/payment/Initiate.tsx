import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';

export default function PaymentInitiate() {
    return (
        <AppLayout>
            <Head title="Initiate Payment" />
            <div className="p-6 bg-white rounded-lg shadow">
                <h2 className="text-xl font-semibold text-gray-800 mb-4">
                    Payment Initiation
                </h2>
                {/* Payment form components */}
            </div>
        </AppLayout>
    );
}
