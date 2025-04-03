import { Head, usePage } from '@inertiajs/react';
import { useEffect } from 'react';

export default function HyperPayForm() {
    const { props } = usePage();
    const { transaction_id, brand_type, widget_url } = props;

    useEffect(() => {
        if (!window.wpwlOptions) {
            window.wpwlOptions = {
                locale: 'ar',
                onReady: function() {
                    console.log('Widget is ready');
                },
                onError: function(error) {
                    console.error('Widget error:', error);
                }
            };
        }

        const script = document.createElement('script');
        script.src = widget_url;
        script.async = true;
        document.body.appendChild(script);

        return () => {
            document.body.removeChild(script);
        };
    }, [widget_url]);

    return (
        <>
            <Head title="Complete Payment" />
            <div className="container mx-auto p-4 max-w-md">
                <form action="#" className="paymentWidgets" data-brands={brand_type}></form>
            </div>
        </>
    );
}
