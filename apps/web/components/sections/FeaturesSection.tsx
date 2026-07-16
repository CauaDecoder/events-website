import { 
  CheckSquare, 
  Gift, 
  Timer, 
  CalendarDays, 
  HelpCircle 
} from "lucide-react";

export function FeaturesSection() {
  const features = [
    { id: 'rsvp', title: 'RSVP Rápido', icon: <CheckSquare size={24} />, desc: 'Convidados confirmam presença em segundos, sem precisar criar conta.' },
    { id: 'presentes', title: 'Lista de Presentes', icon: <Gift size={24} />, desc: 'Adicione links externos para cotas, lojas de varejo ou Pix.' },
    { id: 'countdown', title: 'Contagem Regressiva', icon: <Timer size={24} />, desc: 'Crie expectativa com um cronômetro elegante até a data.' },
    { id: 'timeline', title: 'Agenda do Evento', icon: <CalendarDays size={24} />, desc: 'Informe os horários de cerimônia, recepção e atrações.' },
    { id: 'faq', title: 'FAQ', icon: <HelpCircle size={24} />, desc: 'Tire dúvidas sobre dress code, estacionamento e hospedagem.' },
  ];

  return (
    <section className="section">
      <div className="container">
        <div className="features-container" style={{ display: "flex", flexDirection: "row", alignItems: "flex-start", gap: "48px", flexWrap: "wrap" }}>
          
          <div style={{ flex: 1, maxWidth: "500px", position: "sticky", top: "120px" }}>
            <span className="eyebrow">Módulos Inteligentes</span>
            <h2 style={{ fontSize: "2.5rem", marginBottom: "24px" }}>Tudo que seu evento precisa.</h2>
            <p style={{ color: "var(--text-muted)", fontSize: "1.1rem", lineHeight: 1.6 }}>
              Ligue ou desligue módulos com um clique no painel do organizador. O convite se adapta automaticamente ao que você escolher, mantendo o design sempre impecável.
            </p>
          </div>

          <div style={{ flex: 1, minWidth: "300px" }} className="features-grid">
            {features.map((feat) => (
              <div key={feat.id} style={{ 
                padding: "24px", 
                backgroundColor: "var(--surface)",
                border: "1px solid var(--line)", 
                borderRadius: "var(--radius-md)",
                display: "flex",
                flexDirection: "column",
                gap: "12px"
              }}>
                <div style={{ color: "var(--accent)" }}>{feat.icon}</div>
                <h3 style={{ fontSize: "1.2rem", margin: 0 }}>{feat.title}</h3>
                <p style={{ color: "var(--text-muted)", margin: 0, fontSize: "0.95rem", lineHeight: 1.5 }}>
                  {feat.desc}
                </p>
              </div>
            ))}
          </div>

        </div>
      </div>
    </section>
  );
}
