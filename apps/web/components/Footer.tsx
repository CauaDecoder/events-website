import Link from "next/link";

export function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer style={{ borderTop: "1px solid var(--line)", padding: "48px 0", backgroundColor: "var(--bg-soft)" }}>
      <div className="container" style={{ display: "flex", flexDirection: "column", alignItems: "center", gap: "24px" }}>
        <div className="logo" style={{ color: "var(--text-muted)" }}>Convitê</div>
        
        <div style={{ display: "flex", gap: "24px", color: "var(--text-muted)", fontSize: "0.95rem" }}>
          <Link href="#" style={{ transition: "color 0.2s" }}>Sobre</Link>
          <Link href="#" style={{ transition: "color 0.2s" }}>Termos</Link>
          <Link href="#" style={{ transition: "color 0.2s" }}>Privacidade</Link>
        </div>
        
        <p style={{ color: "var(--text-muted)", fontSize: "0.85rem", margin: 0 }}>
          &copy; {currentYear} Convitê. Criando momentos inesquecíveis.
        </p>
      </div>
    </footer>
  );
}
