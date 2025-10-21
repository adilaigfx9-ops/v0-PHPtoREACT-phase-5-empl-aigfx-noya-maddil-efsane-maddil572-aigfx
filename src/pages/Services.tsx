import { useState, useEffect } from "react"
import { CheckCircle, Clock } from "lucide-react"
import { Button } from "@/components/ui/button"
import { PricingCalculator } from "@/components/pricing-calculator"
import { SEOHead } from "@/components/seo-head"
import { useAnalytics } from "@/utils/analytics"
import { fetchServices } from "@/utils/api"
import { Service } from "@/types"
import { Skeleton } from "@/components/ui/skeleton"

export default function Services() {
  const analytics = useAnalytics()
  const [services, setServices] = useState<Service[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const loadServices = async () => {
      try {
        setLoading(true)
        const data = await fetchServices()
        setServices(data)
      } catch (error) {
        console.error('Failed to load services:', error)
      } finally {
        setLoading(false)
      }
    }

    loadServices()
  }, [])

  return (
    <>
      <SEOHead
        title="Services & Pricing - Professional Design Services | Adil GFX"
        description="Transparent pricing for logo design, YouTube thumbnails, video editing, and complete branding. Choose packages that fit your budget or get a custom quote."
        keywords="logo design pricing, YouTube thumbnail service, video editing rates, branding packages"
      />

      <main className="pt-24 pb-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Page header */}
          <div className="text-center mb-16">
            <h1 className="text-4xl sm:text-5xl font-bold text-foreground mb-6">
              Services & <span className="text-gradient-youtube">Pricing</span>
            </h1>
            <p className="text-xl text-muted-foreground max-w-3xl mx-auto mb-4">
              Professional design services with transparent pricing. Choose the package that fits your needs, 
              or contact me for a custom solution.
            </p>
            <p className="text-lg text-youtube-red font-medium">
              💬 Pricing depends on your specific project requirements. Chat with me for a free personalized quote!
            </p>
          </div>

        {/* Services */}
        {loading ? (
          // Skeleton loading state
          <div className="space-y-20">
            {Array.from({ length: 3 }).map((_, idx) => (
              <div key={idx} className="space-y-12">
                <div className="text-center">
                  <Skeleton className="h-16 w-16 mx-auto mb-4" />
                  <Skeleton className="h-8 w-64 mx-auto mb-4" />
                  <Skeleton className="h-4 w-96 mx-auto" />
                </div>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                  {Array.from({ length: 3 }).map((_, i) => (
                    <div key={i} className="card-premium space-y-4">
                      <Skeleton className="h-6 w-32 mx-auto" />
                      <Skeleton className="h-8 w-40 mx-auto" />
                      <Skeleton className="h-4 w-24 mx-auto" />
                      <div className="space-y-2">
                        {Array.from({ length: 4 }).map((_, j) => (
                          <Skeleton key={j} className="h-4 w-full" />
                        ))}
                      </div>
                      <Skeleton className="h-10 w-full" />
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        ) : services.length === 0 ? (
          <div className="text-center py-20">
            <p className="text-muted-foreground">No services available at the moment.</p>
          </div>
        ) : (
          services.map((service, serviceIndex) => (
            <div key={service.id} className={`mb-20 ${serviceIndex !== services.length - 1 ? 'border-b border-border pb-20' : ''}`}>
              {/* Service header */}
              <div className="text-center mb-12">
                <div className="text-6xl mb-4">{service.icon || '🎨'}</div>
                <h2 className="text-3xl font-bold text-foreground mb-2">{service.name}</h2>
                <p className="text-lg text-youtube-red font-medium mb-4">{service.tagline}</p>
                <p className="text-muted-foreground max-w-2xl mx-auto">{service.description}</p>
              </div>

              {/* Pricing packages */}
              {service.pricingTiers && service.pricingTiers.length > 0 && (
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                  {service.pricingTiers.map((tier, tierIndex) => (
                    <div 
                      key={tierIndex}
                      className={`card-premium relative ${tier.popular ? 'ring-2 ring-youtube-red' : ''}`}
                    >
                      {tier.popular && (
                        <div className="absolute -top-3 left-1/2 transform -translate-x-1/2">
                          <span className="bg-gradient-youtube text-white px-4 py-1 rounded-full text-sm font-medium">
                            Most Popular
                          </span>
                        </div>
                      )}

                      <div className="text-center">
                        <h3 className="text-xl font-semibold text-foreground mb-2">{tier.name}</h3>
                        <div className="text-3xl font-bold text-foreground mb-2">
                          {typeof tier.price === 'number' ? `$${tier.price}` : tier.price}
                        </div>
                        <div className="flex items-center justify-center space-x-2 text-muted-foreground mb-6">
                          <Clock className="h-4 w-4" />
                          <span className="text-sm">{tier.duration || service.deliveryTime}</span>
                        </div>

                        <div className="space-y-3 mb-8">
                          {tier.features && tier.features.map((feature, featureIdx) => (
                            <div key={featureIdx} className="flex items-center space-x-3">
                              <CheckCircle className="h-4 w-4 text-youtube-red flex-shrink-0" />
                              <span className="text-sm text-muted-foreground text-left">{feature}</span>
                            </div>
                          ))}
                        </div>

                        <Button 
                          className={`w-full font-medium ${
                            tier.popular 
                              ? 'bg-gradient-youtube hover:shadow-glow' 
                              : 'variant-outline border-youtube-red text-youtube-red hover:bg-youtube-red hover:text-white'
                          } transition-all duration-300`}
                          onClick={() => window.location.href = '/contact'}
                        >
                          Get Started
                        </Button>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          ))
        )}

        {/* Process section */}
        <div className="mb-20">
          <h2 className="text-3xl font-bold text-foreground mb-8 text-center">How It Works</h2>
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div className="text-center">
              <div className="w-16 h-16 bg-gradient-youtube rounded-xl flex items-center justify-center mx-auto mb-4">
                <span className="text-2xl text-white font-bold">1</span>
              </div>
              <h3 className="font-semibold text-foreground mb-2">Consultation</h3>
              <p className="text-sm text-muted-foreground">We discuss your project, goals, and requirements</p>
            </div>
            
            <div className="text-center">
              <div className="w-16 h-16 bg-gradient-youtube rounded-xl flex items-center justify-center mx-auto mb-4">
                <span className="text-2xl text-white font-bold">2</span>
              </div>
              <h3 className="font-semibold text-foreground mb-2">Design</h3>
              <p className="text-sm text-muted-foreground">I create initial concepts based on your brief</p>
            </div>
            
            <div className="text-center">
              <div className="w-16 h-16 bg-gradient-youtube rounded-xl flex items-center justify-center mx-auto mb-4">
                <span className="text-2xl text-white font-bold">3</span>
              </div>
              <h3 className="font-semibold text-foreground mb-2">Refine</h3>
              <p className="text-sm text-muted-foreground">We collaborate to perfect the design together</p>
            </div>
            
            <div className="text-center">
              <div className="w-16 h-16 bg-gradient-youtube rounded-xl flex items-center justify-center mx-auto mb-4">
                <span className="text-2xl text-white font-bold">4</span>
              </div>
              <h3 className="font-semibold text-foreground mb-2">Deliver</h3>
              <p className="text-sm text-muted-foreground">Final files delivered in all required formats</p>
            </div>
          </div>
        </div>

        {/* Pricing Calculator Section - MOVED TO BOTTOM */}
        <div className="mb-20">
          <h2 className="text-3xl font-bold text-foreground mb-8 text-center">
            Get an Instant <span className="text-gradient-youtube">Quote</span>
          </h2>
          <div className="max-w-2xl mx-auto">
            <PricingCalculator />
          </div>
        </div>

        {/* CTA section */}
        <div className="text-center">
          <div className="bg-gradient-subtle rounded-2xl p-8">
            <h2 className="text-2xl font-bold text-foreground mb-4">
              Not Sure Which Package to Choose?
            </h2>
            <p className="text-muted-foreground mb-6 max-w-2xl mx-auto">
              Pricing depends on your specific project requirements. Chat with me for a free personalized quote that fits your budget and timeline perfectly.
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <Button 
                size="lg"
                className="bg-gradient-youtube hover:shadow-glow transition-all duration-300 font-semibold px-8 py-4"
                onClick={() => window.location.href = '/contact'}
              >
                Get Custom Quote
              </Button>
              <Button 
                size="lg"
                variant="outline"
                className="border-2 border-youtube-red text-youtube-red hover:bg-youtube-red hover:text-white font-semibold px-8 py-4 transition-smooth"
                onClick={() => window.open('https://calendly.com/adilgfx', '_blank')}
              >
                Schedule Free Call
              </Button>
            </div>
          </div>
        </div>
      </div>
    </main>
    </>
  );
}
