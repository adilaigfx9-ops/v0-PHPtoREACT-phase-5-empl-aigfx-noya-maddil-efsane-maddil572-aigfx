import { Star, Quote, ArrowLeft, ArrowRight } from "lucide-react"
import { Button } from "@/components/ui/button"
import { useState, useEffect } from "react"
import { fetchTestimonials } from "@/utils/api"
import { Testimonial } from "@/types"
import { Skeleton } from "@/components/ui/skeleton"

// Case studies remain hardcoded for now (not in database)
const caseStudies = [
  {
    id: 1,
    title: "Gaming Channel Explosion: From 10K to 500K Subscribers",
    client: "Alex Gaming Pro",
    problem: "Low thumbnail CTR (2.1%) and inconsistent branding was limiting channel growth despite quality content",
    solution: "Complete thumbnail strategy overhaul with bold text, high contrast colors, emotion-driven facial expressions, and consistent branding elements",
    result: "CTR increased from 2.1% to 15.8%, gained 490K subscribers in 6 months, monthly revenue grew from $500 to $25,000",
    image: "https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=600&h=400&fit=crop",
    tags: ["YouTube", "Gaming", "Thumbnails"]
  },
  {
    id: 2,
    title: "Startup Brand Revolution: $0 to $10M Valuation",
    client: "TechFlow Solutions",
    problem: "Generic brand identity and amateur logo were hindering investor confidence and customer acquisition",
    solution: "Complete brand overhaul including modern logo design, professional color palette, typography system, and comprehensive brand guidelines",
    result: "Successfully raised $5M Series A funding, 400% increase in website conversions, 300% improvement in brand recognition metrics",
    image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop",
    tags: ["Branding", "Startup", "Logo", "Investment"]
  },
  {
    id: 3,
    title: "E-commerce Visual Transformation: 300% Sales Boost",
    client: "Luxury Lifestyle Co.",
    problem: "Poor visual presentation and inconsistent branding leading to 78% bounce rate and declining sales",
    solution: "Complete visual identity redesign including product photography direction, website banners, social media templates, and brand consistency guidelines",
    result: "Bounce rate dropped to 23%, sales increased by 312% in Q1, customer retention improved by 185%, social media engagement up 450%",
    image: "https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=600&h=400&fit=crop",
    tags: ["E-commerce", "Visual Design", "Sales", "Photography"]
  },
  {
    id: 4,
    title: "YouTube Channel Setup Success: 1M Subscribers in 8 Months",
    client: "Fitness Journey with Maria",
    problem: "New fitness coach with great content but no understanding of YouTube optimization, branding, or growth strategies",
    solution: "Complete YouTube channel setup including channel art, SEO optimization, thumbnail templates, intro/outro videos, content strategy, and growth plan",
    result: "Gained 1.2M subscribers in 8 months, average 2M+ views per video, monthly ad revenue of $45K+, secured 6-figure brand sponsorship deals",
    image: "https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600&h=400&fit=crop",
    tags: ["YouTube", "Channel Setup", "Fitness", "Growth"]
  }
]

export default function Testimonials() {
  const [testimonials, setTestimonials] = useState<Testimonial[]>([])
  const [loading, setLoading] = useState(true)
  const [currentTestimonial, setCurrentTestimonial] = useState(0)

  useEffect(() => {
    const loadTestimonials = async () => {
      try {
        setLoading(true)
        const data = await fetchTestimonials()
        setTestimonials(data)
      } catch (error) {
        console.error('Failed to load testimonials:', error)
      } finally {
        setLoading(false)
      }
    }

    loadTestimonials()
  }, [])

  const nextTestimonial = () => {
    setCurrentTestimonial((prev) => (prev + 1) % testimonials.length)
  }

  const prevTestimonial = () => {
    setCurrentTestimonial((prev) => (prev - 1 + testimonials.length) % testimonials.length)
  }

  return (
    <main className="pt-24 pb-20">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Page header */}
        <div className="text-center mb-16">
          <h1 className="text-4xl sm:text-5xl font-bold text-foreground mb-6">
            Real Stories, Real <span className="text-gradient-youtube">Results</span>
          </h1>
          <p className="text-xl text-muted-foreground max-w-3xl mx-auto">
            Don't just take our word for it. See what clients say about working with Adil and the incredible results they achieved.
          </p>
        </div>

        {loading ? (
          <div className="space-y-20">
            {/* Featured testimonial skeleton */}
            <div className="bg-gradient-subtle rounded-2xl p-8 md:p-12">
              <Skeleton className="h-16 w-16 mx-auto mb-8" />
              <Skeleton className="h-32 w-full max-w-2xl mx-auto mb-8" />
              <div className="flex items-center justify-center space-x-4">
                <Skeleton className="h-16 w-16 rounded-full" />
                <div className="space-y-2">
                  <Skeleton className="h-4 w-32" />
                  <Skeleton className="h-3 w-24" />
                </div>
              </div>
            </div>

            {/* Grid skeleton */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              {Array.from({ length: 6 }).map((_, idx) => (
                <div key={idx} className="card-premium space-y-4">
                  <Skeleton className="h-4 w-full" />
                  <Skeleton className="h-20 w-full" />
                  <div className="flex items-center space-x-3">
                    <Skeleton className="h-10 w-10 rounded-full" />
                    <div className="space-y-2">
                      <Skeleton className="h-3 w-24" />
                      <Skeleton className="h-2 w-32" />
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        ) : testimonials.length === 0 ? (
          <div className="text-center py-20">
            <p className="text-muted-foreground">No testimonials available at the moment.</p>
          </div>
        ) : (
          <>
            {/* Featured testimonial carousel */}
            <div className="mb-20">
              <div className="bg-gradient-subtle rounded-2xl p-8 md:p-12 relative overflow-hidden">
                <div className="relative z-10">
                  <div className="flex items-center justify-center mb-8">
                    <Quote className="h-16 w-16 text-youtube-red opacity-20" />
                  </div>
                  
                  <blockquote className="text-2xl md:text-3xl font-medium text-center text-foreground mb-8 leading-relaxed">
                    "{testimonials[currentTestimonial].content}"
                  </blockquote>
                  
                  <div className="flex items-center justify-center space-x-4">
                    <img 
                      src={testimonials[currentTestimonial].avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(testimonials[currentTestimonial].name)} 
                      alt={testimonials[currentTestimonial].name}
                      className="w-16 h-16 rounded-full object-cover"
                    />
                    <div className="text-center">
                      <div className="font-semibold text-lg text-foreground">
                        {testimonials[currentTestimonial].name}
                      </div>
                      <div className="text-muted-foreground mb-2">
                        {testimonials[currentTestimonial].role}
                      </div>
                      <div className="flex justify-center space-x-1">
                        {[...Array(testimonials[currentTestimonial].rating)].map((_, i) => (
                          <Star key={i} className="h-4 w-4 text-youtube-red fill-current" />
                        ))}
                      </div>
                    </div>
                  </div>
                  
                  <div className="flex items-center justify-center space-x-8 mt-6">
                    <Button
                      variant="ghost"
                      size="sm"
                      onClick={prevTestimonial}
                      className="hover:bg-youtube-red hover:text-white"
                    >
                      <ArrowLeft className="h-4 w-4" />
                    </Button>
                    <div className="flex space-x-2">
                      {testimonials.map((_, index) => (
                        <button
                          key={index}
                          onClick={() => setCurrentTestimonial(index)}
                          className={`w-3 h-3 rounded-full transition-colors ${
                            index === currentTestimonial ? 'bg-youtube-red' : 'bg-muted'
                          }`}
                        />
                      ))}
                    </div>
                    <Button
                      variant="ghost"
                      size="sm"
                      onClick={nextTestimonial}
                      className="hover:bg-youtube-red hover:text-white"
                    >
                      <ArrowRight className="h-4 w-4" />
                    </Button>
                  </div>
                </div>
              </div>
            </div>

            {/* All testimonials grid */}
            <div className="mb-20">
              <h2 className="text-3xl font-bold text-center text-foreground mb-12">
                What Our <span className="text-gradient-youtube">Clients Say</span>
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {testimonials.map((testimonial) => (
                  <div key={testimonial.id} className="card-premium">
                    <div className="flex justify-between items-start mb-4">
                      <div className="flex space-x-1">
                        {[...Array(testimonial.rating)].map((_, i) => (
                          <Star key={i} className="h-4 w-4 text-youtube-red fill-current" />
                        ))}
                      </div>
                      {testimonial.verified && (
                        <span className="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                          Verified
                        </span>
                      )}
                    </div>
                    
                    <blockquote className="text-muted-foreground mb-6 italic">
                      "{testimonial.content}"
                    </blockquote>
                    
                    <div className="flex items-center space-x-3 mb-4">
                      <img 
                        src={testimonial.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(testimonial.name)} 
                        alt={testimonial.name}
                        className="w-10 h-10 rounded-full object-cover"
                      />
                      <div>
                        <div className="font-semibold text-foreground text-sm">{testimonial.name}</div>
                        <div className="text-xs text-muted-foreground">{testimonial.role}</div>
                      </div>
                    </div>
                    
                    {testimonial.projectType && (
                      <div className="border-t border-border pt-4">
                        <div className="flex justify-between text-sm">
                          <span className="text-muted-foreground">Project:</span>
                          <span className="font-medium text-foreground">{testimonial.projectType}</span>
                        </div>
                      </div>
                    )}
                  </div>
                ))}
              </div>
            </div>
          </>
        )}

        {/* Case studies section */}
        <div className="mb-20">
          <h2 className="text-3xl font-bold text-center text-foreground mb-4">
            Success <span className="text-gradient-youtube">Case Studies</span>
          </h2>
          <p className="text-center text-muted-foreground mb-12 max-w-2xl mx-auto">
            Detailed breakdowns of how our designs solved real business problems and delivered measurable results.
          </p>
          
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {caseStudies.map((study) => (
              <div key={study.id} className="card-premium">
                <div className="aspect-video bg-muted rounded-lg mb-6 overflow-hidden">
                  <img 
                    src={study.image} 
                    alt={study.title}
                    className="w-full h-full object-cover"
                  />
                </div>
                
                <div className="flex flex-wrap gap-2 mb-4">
                  {study.tags.map((tag) => (
                    <span 
                      key={tag}
                      className="px-2 py-1 bg-youtube-red/10 text-youtube-red text-xs rounded-md font-medium"
                    >
                      {tag}
                    </span>
                  ))}
                </div>
                
                <h3 className="text-xl font-semibold text-foreground mb-2">{study.title}</h3>
                <p className="text-sm text-muted-foreground mb-4">{study.client}</p>
                
                <div className="space-y-4">
                  <div>
                    <h4 className="font-medium text-foreground text-sm mb-1">Problem:</h4>
                    <p className="text-xs text-muted-foreground">{study.problem}</p>
                  </div>
                  
                  <div>
                    <h4 className="font-medium text-foreground text-sm mb-1">Solution:</h4>
                    <p className="text-xs text-muted-foreground">{study.solution}</p>
                  </div>
                  
                  <div className="bg-gradient-youtube/10 p-3 rounded-lg">
                    <h4 className="font-medium text-youtube-red text-sm mb-1">Result:</h4>
                    <p className="text-xs text-youtube-red font-medium">{study.result}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Trust indicators */}
        <div className="text-center mb-20">
          <h3 className="text-2xl font-bold text-foreground mb-8">
            Trusted Across <span className="text-gradient-youtube">All Platforms</span>
          </h3>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div className="card-premium text-center">
              <Star className="h-8 w-8 text-youtube-red mx-auto mb-3 fill-current" />
              <div className="text-2xl font-bold text-foreground">5.0</div>
              <div className="text-sm text-muted-foreground">Fiverr Rating</div>
              <div className="text-xs text-muted-foreground mt-1">350+ Reviews</div>
            </div>
            <div className="card-premium text-center">
              <Star className="h-8 w-8 text-youtube-red mx-auto mb-3 fill-current" />
              <div className="text-2xl font-bold text-foreground">Level 2</div>
              <div className="text-sm text-muted-foreground">Fiverr Seller</div>
              <div className="text-xs text-muted-foreground mt-1">Top Rated</div>
            </div>
            <div className="card-premium text-center">
              <Star className="h-8 w-8 text-youtube-red mx-auto mb-3 fill-current" />
              <div className="text-2xl font-bold text-foreground">500+</div>
              <div className="text-sm text-muted-foreground">Happy Clients</div>
              <div className="text-xs text-muted-foreground mt-1">Worldwide</div>
            </div>
            <div className="card-premium text-center">
              <Star className="h-8 w-8 text-youtube-red mx-auto mb-3 fill-current" />
              <div className="text-2xl font-bold text-foreground">24-48h</div>
              <div className="text-sm text-muted-foreground">Delivery</div>
              <div className="text-xs text-muted-foreground mt-1">Guaranteed</div>
            </div>
          </div>
        </div>

        {/* CTA section */}
        <div className="text-center">
          <div className="bg-gradient-subtle rounded-2xl p-8">
            <h2 className="text-2xl font-bold text-foreground mb-4">
              Ready to Join These Success Stories?
            </h2>
            <p className="text-muted-foreground mb-6 max-w-2xl mx-auto">
              Let's create designs that don't just look amazing, but drive real results for your business or channel.
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <Button 
                size="lg"
                className="bg-gradient-youtube hover:shadow-glow transition-all duration-300 font-semibold px-8 py-4"
                onClick={() => window.location.href = '/contact'}
              >
                Start Your Project Today
              </Button>
              <Button 
                variant="outline"
                size="lg"
                className="border-2 border-youtube-red text-youtube-red hover:bg-youtube-red hover:text-white font-semibold px-8 py-4"
                onClick={() => window.location.href = '/portfolio'}
              >
                View Portfolio
              </Button>
            </div>
          </div>
        </div>
      </div>
    </main>
  )
}
