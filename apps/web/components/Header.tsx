import Link from "next/link";
import { ThemeToggle } from "./ThemeToggle";
import { Sparkles } from "lucide-react";

export function Header() {
  return (
    <header className="header">
      <div className="container header-container">
        <Link href="/" className="logo">
          <Sparkles className="text-gradient" size={24} />
          Convitê
        </Link>
        <div className="header-actions">
          <ThemeToggle />
          <Link href="#" className="btn btn-secondary">
            Entrar
          </Link>
          <Link href="#" className="btn btn-primary" style={{ display: "none" /* Oculto no mobile, mostrar depois se precisar */ }}>
            Criar Evento
          </Link>
        </div>
      </div>
    </header>
  );
}
