export function HowItWorksSection() {
  const steps = [
    { num: '01', title: 'Cadastre-se', desc: 'Crie sua conta gratuitamente em menos de um minuto e dê um nome ao seu evento.' },
    { num: '02', title: 'Personalize', desc: 'Escolha um template, ajuste cores, fontes e ligue apenas os módulos que vai usar.' },
    { num: '03', title: 'Compartilhe', desc: 'Envie o link personalizado por WhatsApp, e-mail ou redes sociais e acompanhe os RSVPs.' },
  ];

  return (
    <section className="section" id="como-funciona" style={{ backgroundColor: "var(--bg-soft)", borderTop: "1px solid var(--line)" }}>
      <div className="container">
        <div className="text-center mb-12">
          <span className="eyebrow">Simplicidade</span>
          <h2 style={{ fontSize: "2.5rem" }}>Como funciona</h2>
        </div>

        <div className="how-it-works-grid">
          {steps.map((step) => (
            <div key={step.num} style={{
              display: "flex",
              flexDirection: "column",
              alignItems: "center",
              textAlign: "center",
              gap: "16px",
              padding: "24px"
            }}>
              <div style={{ 
                fontFamily: "var(--font-mono)",
                fontSize: "3rem",
                fontWeight: "700",
                color: "var(--accent-soft)",
                lineHeight: 1
              }}>
                {step.num}
              </div>
              <h3 style={{ fontSize: "1.5rem", margin: 0 }}>{step.title}</h3>
              <p style={{ color: "var(--text-muted)", margin: 0, fontSize: "1rem", lineHeight: 1.6, maxWidth: "300px" }}>
                {step.desc}
              </p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
