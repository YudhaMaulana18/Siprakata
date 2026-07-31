class AppUser {
  final int id;
  final String name;
  final String email;
  final String? roleId;
  final Role? role;

  AppUser({
    required this.id,
    required this.name,
    required this.email,
    this.roleId,
    this.role,
  });

  factory AppUser.fromJson(Map<String, dynamic> json) {
    return AppUser(
      id: json['id'],
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      roleId: json['role_id']?.toString(),
      role: json['role'] != null ? Role.fromJson(json['role']) : null,
    );
  }
}

class Role {
  final int id;
  final String name;
  final String? displayName;

  Role({required this.id, required this.name, this.displayName});

  factory Role.fromJson(Map<String, dynamic> json) {
    return Role(
      id: json['id'],
      name: json['name'] ?? '',
      displayName: json['display_name'],
    );
  }
}
