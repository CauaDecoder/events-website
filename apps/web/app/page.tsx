import { Header } from "../components/Header";
import { Footer } from "../components/Footer";
import { HeroSection } from "../components/sections/HeroSection";
import { EventTypesSection } from "../components/sections/EventTypesSection";
import { FeaturesSection } from "../components/sections/FeaturesSection";
import { HowItWorksSection } from "../components/sections/HowItWorksSection";

export default function HomePage() {
  return (
    <>
      <Header />
      <main>
        <HeroSection />
        <EventTypesSection />
        <FeaturesSection />
        <HowItWorksSection />
      </main>
      <Footer />
    </>
  );
}
