import 'package:flutter/material.dart';
import '../models/user_model.dart';
import '../models/mahasiswa_model.dart';
import '../services/api_service.dart';

class AuthProvider extends ChangeNotifier {
  final ApiService _api = ApiService();

  AppUser? _user;
  Mahasiswa? _mahasiswa;
  bool _isLoading = false;
  bool _isInitialLoading = true;
  String? _error;

  AppUser? get user => _user;
  Mahasiswa? get mahasiswa => _mahasiswa;
  bool get isLoading => _isLoading;
  bool get isInitialLoading => _isInitialLoading;
  bool get isLoggedIn => _user != null;
  String? get error => _error;

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final result = await _api.login(email, password);
      if (result['status'] == 'success') {
        final user = AppUser.fromJson(result['data']['user']);
        _user = user;
        _isLoading = false;
        _isInitialLoading = false;
        notifyListeners();
        if (user.isMahasiswa) {
          if (result['data']['mahasiswa'] != null) {
            _mahasiswa = Mahasiswa.fromJson(result['data']['mahasiswa']);
            notifyListeners();
          } else {
            await _loadMahasiswaData();
          }
        }
        return true;
      } else {
        _error = result['message'] ?? 'Login gagal';
        _isLoading = false;
        _isInitialLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _error = 'Terjadi kesalahan: $e';
      _isLoading = false;
      _isInitialLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> loadUser() async {
    _isInitialLoading = true;
    notifyListeners();

    try {
      final result = await _api.getMe();
      if (result['status'] == 'success') {
        _user = AppUser.fromJson(result['data']);
        if (_user!.isMahasiswa) {
          await _loadMahasiswaData();
        }
      } else {
        _user = null;
        _mahasiswa = null;
        await _api.clearToken();
      }
    } catch (e) {
      _user = null;
      _mahasiswa = null;
    }

    _isInitialLoading = false;
    notifyListeners();
  }

  Future<void> _loadMahasiswaData() async {
    if (_user == null) return;

    try {
      final result = await _api.getMahasiswaList();
      if (result['status'] == 'success' && result['data'] != null) {
        final list = (result['data'] as List)
            .map((e) => Mahasiswa.fromJson(e))
            .toList();
        _mahasiswa = list.where((m) =>
          m.email != null && m.email == _user!.email
        ).firstOrNull;
        notifyListeners();
      }
    } catch (e) {
      // Silent fail
    }
  }

  Future<void> logout() async {
    await _api.logout();
    _user = null;
    _mahasiswa = null;
    _error = null;
    _isInitialLoading = false;
    notifyListeners();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
