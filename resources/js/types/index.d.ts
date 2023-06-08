export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

export interface CryptoCurrencyMetadata {
    id: number;
    name: string;
    slug: string;
    symbol: string;
    description: string;
    date_added: string;
    date_launched: string;
    tags: string[];
    category: string;
    logo_url: string;
    urls: string;
}

export interface CryptoCurrency {
    id: number;
    name: string;
    slug: string;
    symbol: string;
    price: number;
    percent_change_1h: number;
    percent_change_24h: number;
    market_cap: number;
    percent_change_7d: number;
    volume_change_24h: number;
    volume_change_24h_btc: number;
    circular_supply: number;
    links: {
        self: string;
    }
}
export interface CryptoCurrencyWithMetadata extends CryptoCurrency {
    metadata: CryptoCurrencyMetadata;
}

export interface LinkProps {
    active: boolean;
    label: string;
    url: string;
}

export interface PaginationData {
    id: number;
    current_page: number;
    data: CryptoCurrency[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: LinkProps[];
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string;
    to: number;
    total: number;
}

export interface LinkProps {
    active: boolean;
    label: string;
    url: string;
}

export interface PaginationData {
    // id: number;
    current_page: number;
    data: any[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: LinkProps[];
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string;
    to: number;
    total: number;
}