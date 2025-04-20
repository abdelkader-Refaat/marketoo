import { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Pagination, Navigation } from 'swiper/modules';
import axios from 'axios';
import { Loader2, ChevronLeft, ChevronRight } from 'lucide-react';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

interface IntroSlider {
    id: number;
    image: string;
    title: string;
    description: string;
}

const FALLBACK_SLIDERS: IntroSlider[] = [
    {
        id: 1,
        image: 'https://images.unsplash.com/photo-1488590528505-98d2b5aba04b',
        title: 'Welcome to Your Dashboard',
        description: 'Manage your content efficiently with our powerful tools'
    },
    {
        id: 2,
        image: 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6',
        title: 'Powerful Features',
        description: 'Access all the features you need to grow your business'
    }
];

export default function Slider() {
    const { locale } = usePage().props;
    const [sliders, setSliders] = useState<IntroSlider[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        fetchSliders();
    }, [locale]);

    const fetchSliders = async () => {
        try {
            setLoading(true);
            setError(null);
            // Update API endpoint to match your Laravel route
            const response = await axios.get('/site/intro-sliders');

            console.log('API Response:', response.data);

            if (!response.data || response.data.length === 0) {
                console.log('No sliders from API, using fallback data');
                setSliders(FALLBACK_SLIDERS);
            } else {
                setSliders(response.data);
            }
        } catch (err: any) {
            console.error('API Error:', err);
            console.log('Using fallback data due to API error');
            setSliders(FALLBACK_SLIDERS);
            setError('Could not load from server. Showing default content.');
        } finally {
            setLoading(false);
        }
    };

    if (loading) {
        return (
            <div
                className="min-h-[500px] w-full bg-muted rounded-xl animate-pulse flex flex-col items-center justify-center gap-4">
                <Loader2 className="h-8 w-8 animate-spin text-muted-foreground" />
                <span className="text-muted-foreground">Loading content...</span>
            </div>
        );
    }

    return (
        <div className="relative w-full rounded-xl overflow-hidden border border-border bg-card">
            <Swiper
                modules={[Autoplay, Pagination, Navigation]}
                spaceBetween={0}
                centeredSlides={true}
                autoplay={{
                    delay: 5000,
                    disableOnInteraction: false
                }}
                pagination={{
                    clickable: true
                }}
                navigation={true}
                className="heroSlider h-[500px] md:h-[600px]"
            >
                {sliders.map((slider) => (
                    <SwiperSlide key={slider.id}>
                        <div className="relative w-full h-full">
                            <img
                                src={slider.image}
                                alt={slider.title}
                                className="w-full h-full object-cover"
                                onError={(e) => {
                                    const target = e.target as HTMLImageElement;
                                    target.src = 'https://images.unsplash.com/photo-1488590528505-98d2b5aba04b';
                                    target.onerror = null; // Prevent infinite loop
                                }}
                            />
                            <div
                                className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                                <div className="absolute bottom-0 left-0 right-0 p-8 text-white">
                                    <div className="max-w-3xl mx-auto text-center">
                                        <h2 dir="auto"
                                            className="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 text-white">
                                            {slider.title}
                                        </h2>
                                        <p dir="auto" className="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto">
                                            {slider.description}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </SwiperSlide>
                ))}

                <div className="swiper-button-prev custom-nav-btn">
                    <ChevronLeft className="w-6 h-6" />
                </div>
                <div className="swiper-button-next custom-nav-btn">
                    <ChevronRight className="w-6 h-6" />
                </div>
            </Swiper>

            {error && (
                <div className="absolute top-4 right-4 bg-destructive/10 text-destructive px-4 py-2 rounded-lg text-sm">
                    {error}
                    <button onClick={fetchSliders} className="ml-2 underline">
                        Retry
                    </button>
                </div>
            )}
        </div>
    );
}
