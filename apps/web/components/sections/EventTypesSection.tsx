import { 
  Heart, 
  Cake, 
  GraduationCap, 
  GlassWater, 
  Baby, 
  Briefcase 
} from "lucide-react";

export function EventTypesSection() {
  const events = [
    { id: 'casamento', title: 'Casamento', icon: <Heart size={32} strokeWidth={1.5} />, desc: 'Elegância e organização para o grande dia.' },
    { id: 'aniversario', title: 'Aniversário', icon: <Cake size={32} strokeWidth={1.5} />, desc: 'Celebre mais um ano de vida com estilo.' },
    { id: 'formatura', title: 'Formatura', icon: <GraduationCap size={32} strokeWidth={1.5} />, desc: 'Compartilhe sua conquista com quem importa.' },
    { id: 'debutante', title: 'Debutante', icon: <GlassWater size={32} strokeWidth={1.5} />, desc: 'A festa dos 15 anos perfeita começa aqui.' },
    { id: 'chadebebe', title: 'Chá de Bebê', icon: <Baby size={32} strokeWidth={1.5} />, desc: 'Reúna a família para a chegada do novo membro.' },
    { id: 'corporativo', title: 'Corporativo', icon: <Briefcase size={32} strokeWidth={1.5} />, desc: 'Eventos empresariais, workshops e confraternizações.' },
  ];

  return (
    <section className="section" style={{ backgroundColor: "var(--bg-soft)" }}>
      <div className="container">
        <div className="text-center mb-12">
          <span className="eyebrow">Flexibilidade</span>
          <h2 style={{ fontSize: "2.5rem" }}>Para todo tipo de celebração</h2>
          <p style={{ color: "var(--text-muted)", marginTop: "16px", maxWidth: "600px", margin: "16px auto 0" }}>
            Nossa plataforma não é apenas para casamentos. Crie eventos com a cara da sua festa, ativando apenas os módulos que precisa.
          </p>
        </div>

        <div className="event-types-grid">
          {events.map((evt) => (
            <div key={evt.id} style={{
              backgroundColor: "var(--surface)",
              borderRadius: "var(--radius-md)",
              padding: "32px",
              border: "1px solid var(--line)",
              boxShadow: "var(--shadow)",
              transition: "transform 0.2s ease, box-shadow 0.2s ease",
              display: "flex",
              flexDirection: "column",
              alignItems: "flex-start",
              gap: "16px"
            }}
            className="hover-card"
            >
              <div style={{ 
                width: "64px", 
                height: "64px", 
                borderRadius: "var(--radius-full)", 
                backgroundColor: "var(--accent-soft)", 
                color: "var(--accent)", 
                display: "flex", 
                alignItems: "center", 
                justifyContent: "center" 
              }}>
                {evt.icon}
              </div>
              <h3 style={{ fontSize: "1.25rem", margin: 0 }}>{evt.title}</h3>
              <p style={{ color: "var(--text-muted)", margin: 0, fontSize: "0.95rem", lineHeight: 1.5 }}>
                {evt.desc}
              </p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
