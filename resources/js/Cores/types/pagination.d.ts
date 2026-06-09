export interface Paginated<T> {
    data: T[];
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    current_page: number;
    total: number;
    from: number;
    to: number;
    last_page: number;
    per_page: number;
}
