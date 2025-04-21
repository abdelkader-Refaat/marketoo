import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Pagination, Navigation } from 'swiper/modules';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

interface IntroSlider {
    id: number;
    image: string;
    title: string;
    description: string;
}

interface SliderProps {
    sliders: IntroSlider[];
}

const FALLBACK_SLIDERS: IntroSlider[] = [
    {
        id: 1,
        image: '/images/fallback-slide.jpg',
        title: 'Welcome to Your Dashboard',
        description: 'Manage your content efficiently with our powerful tools'
    }
];

export default function Slider({ sliders = FALLBACK_SLIDERS }: SliderProps) {
    // Use the passed sliders or fallback if none provided
    const slidesToShow = sliders.length ? sliders : FALLBACK_SLIDERS;

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
                className="h-[500px] md:h-[600px]"
            >
                {slidesToShow.map((slider) => (
                    <SwiperSlide key={slider.id}>
                        <div className="relative w-full h-full">
                            <img
                                src={slider.image}
                                alt={slider.title}
                                className="w-full h-full object-cover"
                                onError={(e) => {
                                    const target = e.target as HTMLImageElement;
                                    target.src = '/images/fallback-slide.jpg';
                                    target.onerror = null;
                                }}
                            />
                            <div
                                className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                                <div className="absolute bottom-0 left-0 right-0 p-8 text-white">
                                    <div className="max-w-3xl mx-auto text-center">
                                        <h2 className="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">
                                            {slider.title}
                                        </h2>
                                        <p className="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto">
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
        </div>
    );
}
