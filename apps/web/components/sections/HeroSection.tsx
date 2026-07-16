import Link from "next/link";
import { ArrowRight } from "lucide-react";

export function HeroSection() {
  return (
    <section className="section hero-section" style={{ position: "relative", overflow: "hidden", minHeight: "85vh", display: "flex", alignItems: "center" }}>
      <div className="hero-bg-pattern">
        <div className="hero-blob-1"></div>
        <div className="hero-blob-2"></div>
      </div>
      
      <div className="container" style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: "48px", alignItems: "center" }}>
        <div style={{ maxWidth: "600px" }}>
          <span className="eyebrow">A plataforma definitiva</span>
          <h1 style={{ fontSize: "clamp(3rem, 5vw, 4.5rem)", marginBottom: "24px" }}>
            Convites digitais que <span className="text-gradient">encantam</span>.
          </h1>
          <p className="lead" style={{ fontSize: "1.1rem", lineHeight: "1.6", color: "var(--text-muted)", marginBottom: "32px", maxWidth: "480px" }}>
            Crie, personalize e compartilhe convites interativos para qualquer celebração. RSVP integrado, lista de presentes e tudo em um só lugar, gratuitamente.
          </p>
          <div style={{ display: "flex", gap: "16px", flexWrap: "wrap" }}>
            <Link href="#" className="btn btn-primary" style={{ padding: "0 32px", height: "56px", fontSize: "1.1rem" }}>
              Crie seu convite grátis
            </Link>
            <Link href="#como-funciona" className="btn btn-secondary" style={{ padding: "0 24px", height: "56px" }}>
              Ver como funciona <ArrowRight size={18} />
            </Link>
          </div>
        </div>
        
        <div style={{ position: "relative", borderRadius: "var(--radius-lg)", overflow: "hidden", boxShadow: "var(--shadow)", border: "1px solid var(--line)", aspectRatio: "4/3", backgroundColor: "var(--surface)", display: "flex", flexDirection: "column" }}>
            {/* Mockup do Dashboard/Convite inspirado no insp-1.png */}
            <div style={{ height: "48px", borderBottom: "1px solid var(--line)", display: "flex", alignItems: "center", padding: "0 16px", gap: "8px", backgroundColor: "var(--surface-strong)" }}>
               <div style={{ width: "12px", height: "12px", borderRadius: "50%", backgroundColor: "#ff5f56" }}></div>
               <div style={{ width: "12px", height: "12px", borderRadius: "50%", backgroundColor: "#ffbd2e" }}></div>
               <div style={{ width: "12px", height: "12px", borderRadius: "50%", backgroundColor: "#27c93f" }}></div>
               <div style={{ margin: "0 auto", fontSize: "0.75rem", fontFamily: "var(--font-mono)", color: "var(--text-muted)", backgroundColor: "var(--surface)", padding: "4px 12px", borderRadius: "4px", border: "1px solid var(--line)" }}>convite.com/marina-e-lucas</div>
            </div>
            <div style={{ flex: 1, padding: "32px", display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", textAlign: "center", background: "linear-gradient(to bottom, #f5efe5, #fff)" }}>
               <span style={{ fontSize: "0.8rem", letterSpacing: "0.2em", textTransform: "uppercase", color: "#6d6258", marginBottom: "16px", fontFamily: "var(--font-mono)" }}>12 SET 2026</span>
               <h2 style={{ fontSize: "2.5rem", fontFamily: "var(--font-display)", color: "#1f1a17", marginBottom: "16px" }}>Marina & Lucas</h2>
               <p style={{ color: "#6d6258", fontSize: "0.9rem", maxWidth: "300px", marginBottom: "24px" }}>Vamos celebrar o amor ao entardecer no Jardim das Acácias.</p>
               <button style={{ backgroundColor: "#8f4c3f", color: "#fff", border: "none", padding: "12px 24px", borderRadius: "99px", fontSize: "0.9rem", fontWeight: "500" }}>Confirmar Presença</button>
            </div>
        </div>
      </div>
    </section>
  );
}
