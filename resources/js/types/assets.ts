export type AssetStatus =
    | 'available'
    | 'in_use'
    | 'under_repair'
    | 'disposed';

export type SelectOption<TValue = string> = {
    value: TValue;
    label: string;
};

export type AssetListItem = {
    id: number;
    name: string;
    serial_number: string | null;
    brand: string | null;
    current_location: string | null;
    status: AssetStatus;
    status_label: string;
    category: string;
};

export type AssetDetail = {
    id: number;
    name: string;
    serial_number: string | null;
    model_number: string | null;
    brand: string | null;
    purchase_date: string | null;
    cost: string | null;
    current_location: string | null;
    notes: string | null;
    status: AssetStatus;
    status_label: string;
    category: string;
};

export type AssetFormData = {
    id?: number;
    category_id: number | null;
    name: string;
    serial_number: string | null;
    model_number: string | null;
    brand: string | null;
    purchase_date: string | null;
    cost: string | null;
    status: AssetStatus;
    current_location: string | null;
    notes: string | null;
};

export type AssetLog = {
    id: number;
    action: string;
    action_label: string;
    description: string;
    user: string | null;
    created_at: string | null;
};

export type Paginated<T> = {
    data: T[];
    links: Array<{ url: string | null; label: string; active: boolean }>;
    current_page: number;
    last_page: number;
    from: number | null;
    to: number | null;
    total: number;
};

export type AssetFilters = {
    search: string;
    status: string;
    category: number | null;
};
