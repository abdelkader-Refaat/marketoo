export function ErrorMessage({ message }: { message?: string }) {
    if (!message) return null;
    return <p className="mt-1 text-sm text-red-500">{message}</p>;
}