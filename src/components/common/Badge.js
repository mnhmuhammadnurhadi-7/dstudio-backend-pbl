const statusMap = {
  terkirim: 'bg-yellow-100 text-yellow-800',
  diproses: 'bg-purple-100 text-purple-800',
  selesai: 'bg-green-100 text-green-800',
  revisi: 'bg-orange-100 text-orange-800',
  dibatalkan: 'bg-red-100 text-red-800',
};

export function StatusBadge({ status }) {
  const cls = statusMap[status] ?? 'bg-gray-100 text-gray-800';
  return (
    <span className={`inline-block px-2 py-1 rounded-full text-xs font-semibold uppercase ${cls}`}>
      {status}
    </span>
  );
}

export function RoleBadge({ role }) {
  const roleMap = {
    superadmin: 'bg-purple-100 text-purple-800',
    admin: 'bg-blue-100 text-blue-800',
  };
  const cls = roleMap[role] ?? 'bg-gray-100 text-gray-800';
  return (
    <span className={`inline-block px-2 py-1 rounded-full text-xs font-semibold ${cls}`}>
      {role}
    </span>
  );
}
