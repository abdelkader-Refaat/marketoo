import { Head, Link } from '@inertiajs/react';

export default function Welcome({ auth }: { auth: { user?: any } }) {
    return (
        <>
            <Head title="Welcome to Marketoo" />

            <div
                className="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-white to-gray-100 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-white">
                <div className="container mx-auto px-6 py-16 flex flex-col md:flex-row items-center justify-between">

                    {/* Left Side: Introduction */}
                    <div className="max-w-xl text-center md:text-left">
                        <h1 className="text-4xl font-bold mb-4">
                            Welcome to <span className="text-blue-600 dark:text-blue-400">Marketoo</span>
                        </h1>
                        <p className="text-lg mb-6">
                            Discover the best deals, services, and collaborations on Marketoo.
                        </p>

                        {/* Show Dashboard if Authenticated, Otherwise Show Login/Register */}
                        {auth.user ? (
                            <Link
                                href="site/dashboard"
                                className="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            >
                                Go to Dashboard
                            </Link>
                        ) : (
                            <div className="flex gap-4 justify-center md:justify-start">
                                <Link
                                    href="/site/login"
                                    className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                >
                                    Login
                                </Link>
                                <Link
                                    href="/site/register"
                                    className="px-6 py-3 bg-gray-100 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition"
                                >
                                    Register
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Right Side: Hero Image */}
                    <div className="mt-10 md:mt-0 flex justify-center">
                        <img
                            src="/storage/images/marketoo.png"
                            alt="Marketoo preview"
                            className="max-w-sm w-full rounded-lg shadow-lg"
                        />
                    </div>
                </div>
            </div>
        </>
    );
}
