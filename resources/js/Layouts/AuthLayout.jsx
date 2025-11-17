import { Head } from '@inertiajs/react';

export default function AuthLayout({ children, title }) {
    return (
        <div className="min-h-screen bg-gray-100">
            <Head>
                <title>{title ? `${title} - Admin` : 'Admin'}</title>
                <meta name="csrf-token" content={document.querySelector('meta[name="csrf-token"]').content} />
            </Head>

            <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <div className="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {children}
                </div>
            </div>
        </div>
    );
}
