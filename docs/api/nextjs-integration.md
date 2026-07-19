# Integração com Next.js

## Endpoint público

```text
GET /api/v1/public/invitations/{slug}
```

Somente snapshots publicados são retornados. Alterações feitas no builder não
afetam o site público até o cliente clicar em **Publicar** novamente.

## Busca no App Router

```ts
const API_URL = process.env.LARAVEL_API_URL!;

export async function getInvitation(slug: string): Promise<Invitation> {
  const response = await fetch(
    `${API_URL}/api/v1/public/invitations/${encodeURIComponent(slug)}`,
    { next: { revalidate: 60, tags: [`invitation:${slug}`] } },
  );

  if (!response.ok) throw new Error('Convite não encontrado');

  const body = await response.json();
  return body.data;
}
```

## Tipos mínimos

```ts
type BuilderBlock = {
  id: string;
  type: 'heading' | 'text' | 'button' | 'image' | 'divider' | 'spacer' |
    'countdown' | 'gallery' | 'map' | 'video';
  props: Record<string, unknown>;
  styles: { padding?: string; background?: string };
};

type InvitationPage = {
  id: number;
  name: string;
  slug: string;
  is_home: boolean;
  zones: {
    header: BuilderBlock[];
    content: BuilderBlock[];
    footer: BuilderBlock[];
  };
};

type Invitation = {
  schema_version: 1;
  event: { id: number; name: string; type: string; date: string | null };
  site: { id: number; name: string; slug: string; theme: Record<string, string> };
  pages: InvitationPage[];
};
```

No Next.js, cada `block.type` deve ser mapeado para um componente React. Nunca
renderize HTML arbitrário vindo da API; as propriedades devem ser passadas aos
componentes conhecidos do renderer.
# Resolução por domínio personalizado

Quando o domínio estiver verificado e vinculado a um site publicado, o Next.js pode resolver o convite pelo hostname recebido na requisição:

```http
GET /api/v1/public/domains/convite.exemplo.com/invitation
```

O payload é o mesmo do endpoint por slug. Use o header `Host` do frontend para montar o hostname, normalize para minúsculas e encaminhe-o nesse endpoint.
