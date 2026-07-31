import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../config/api_config.dart';

class ApiService {
  static final ApiService _instance = ApiService._internal();
  factory ApiService() => _instance;
  ApiService._internal();

  final FlutterSecureStorage _storage = const FlutterSecureStorage();
  String? _token;

  Future<String?> get token async {
    _token ??= await _storage.read(key: 'auth_token');
    return _token;
  }

  Future<void> setToken(String token) async {
    _token = token;
    await _storage.write(key: 'auth_token', value: token);
  }

  Future<void> clearToken() async {
    _token = null;
    await _storage.delete(key: 'auth_token');
  }

  Future<Map<String, String>> _headers() async {
    final t = await token;
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (t != null) 'Authorization': 'Bearer $t',
    };
  }

  Future<Map<String, dynamic>> get(String endpoint, {Map<String, String>? queryParams}) async {
    try {
      var uri = Uri.parse('${ApiConfig.baseUrl}$endpoint');
      if (queryParams != null) {
        uri = uri.replace(queryParameters: queryParams);
      }
      debugPrint('GET $uri');
      final response = await http.get(uri, headers: await _headers()).timeout(ApiConfig.timeout);
      debugPrint('Response [${response.statusCode}]: ${response.body}');
      return _handleResponse(response);
    } catch (e) {
      debugPrint('GET $endpoint ERROR: $e');
      return {'status': 'error', 'message': 'Koneksi gagal: $e', 'data': null};
    }
  }

  Future<Map<String, dynamic>> post(String endpoint, {Map<String, dynamic>? body}) async {
    try {
      final uri = Uri.parse('${ApiConfig.baseUrl}$endpoint');
      debugPrint('POST $uri');
      debugPrint('Body: ${jsonEncode(body)}');
      final response = await http.post(uri, headers: await _headers(), body: body != null ? jsonEncode(body) : null).timeout(ApiConfig.timeout);
      debugPrint('Response [${response.statusCode}]: ${response.body}');
      return _handleResponse(response);
    } catch (e) {
      debugPrint('POST $endpoint ERROR: $e');
      return {'status': 'error', 'message': 'Koneksi gagal: $e', 'data': null};
    }
  }

  Future<Map<String, dynamic>> put(String endpoint, {Map<String, dynamic>? body}) async {
    try {
      final uri = Uri.parse('${ApiConfig.baseUrl}$endpoint');
      final response = await http.put(uri, headers: await _headers(), body: body != null ? jsonEncode(body) : null).timeout(ApiConfig.timeout);
      return _handleResponse(response);
    } catch (e) {
      return {'status': 'error', 'message': 'Koneksi gagal: $e', 'data': null};
    }
  }

  Future<Map<String, dynamic>> delete(String endpoint) async {
    try {
      final uri = Uri.parse('${ApiConfig.baseUrl}$endpoint');
      final response = await http.delete(uri, headers: await _headers()).timeout(ApiConfig.timeout);
      return _handleResponse(response);
    } catch (e) {
      return {'status': 'error', 'message': 'Koneksi gagal: $e', 'data': null};
    }
  }

  Map<String, dynamic> _handleResponse(http.Response response) {
    try {
      final body = jsonDecode(response.body);
      return body;
    } catch (e) {
      return {'status': 'error', 'message': 'Gagal memproses respons: ${response.body}', 'data': null};
    }
  }

  void debugPrint(String msg) {
    // ignore: avoid_print
    print('[API] $msg');
  }

  Future<Map<String, dynamic>> login(String email, String password) async {
    final result = await post('login', body: {'email': email, 'password': password});
    if (result['status'] == 'success' && result['data'] != null) {
      await setToken(result['data']['token']);
    }
    return result;
  }

  Future<Map<String, dynamic>> logout() async {
    final result = await post('logout');
    await clearToken();
    return result;
  }

  Future<Map<String, dynamic>> getMe() async {
    return await get('me');
  }

  Future<Map<String, dynamic>> getMahasiswaList({String? search}) async {
    final params = <String, String>{};
    if (search != null && search.isNotEmpty) params['search'] = search;
    return await get('mahasiswa', queryParams: params.isNotEmpty ? params : null);
  }

  Future<Map<String, dynamic>> getMahasiswaDetail(int id) async {
    return await get('mahasiswa/$id');
  }

  Future<Map<String, dynamic>> getKRSList({String? search}) async {
    final params = <String, String>{};
    if (search != null && search.isNotEmpty) params['search'] = search;
    return await get('krs', queryParams: params.isNotEmpty ? params : null);
  }

  Future<Map<String, dynamic>> getKRSDetail(int id) async {
    return await get('krs/$id');
  }

  Future<Map<String, dynamic>> createKRS({
    required int mahasiswaId,
    required int matakuliahId,
    required int dosenId,
    required String tahunAjaran,
    required String semester,
    String? status,
  }) async {
    return await post('krs', body: {
      'mahasiswa_id': mahasiswaId,
      'matakuliah_id': matakuliahId,
      'dosen_id': dosenId,
      'tahun_ajaran': tahunAjaran,
      'semester': semester,
      'status': ?status,
    });
  }

  Future<Map<String, dynamic>> getJadwalList({String? hari, String? matakuliahIds}) async {
    final params = <String, String>{};
    if (hari != null && hari.isNotEmpty) params['hari'] = hari;
    if (matakuliahIds != null && matakuliahIds.isNotEmpty) params['matakuliah_id'] = matakuliahIds;
    return await get('jadwal', queryParams: params.isNotEmpty ? params : null);
  }

  Future<Map<String, dynamic>> getJadwalDetail(int id) async {
    return await get('jadwal/$id');
  }

  Future<Map<String, dynamic>> getPresensiList({int? jadwalId, int? mahasiswaId}) async {
    final params = <String, String>{};
    if (jadwalId != null) params['jadwal_id'] = jadwalId.toString();
    if (mahasiswaId != null) params['mahasiswa_id'] = mahasiswaId.toString();
    return await get('presensi', queryParams: params.isNotEmpty ? params : null);
  }

  Future<Map<String, dynamic>> getNilaiList() async {
    return await get('nilai');
  }

  Future<Map<String, dynamic>> getNilaiDetail(int id) async {
    return await get('nilai/$id');
  }

  Future<Map<String, dynamic>> getMateriList({int? jadwalId}) async {
    final params = <String, String>{};
    if (jadwalId != null) params['jadwal_id'] = jadwalId.toString();
    return await get('materi', queryParams: params.isNotEmpty ? params : null);
  }

  Future<Map<String, dynamic>> getPengumumanList({String? prioritas}) async {
    final params = <String, String>{};
    if (prioritas != null && prioritas.isNotEmpty) params['prioritas'] = prioritas;
    return await get('pengumuman', queryParams: params.isNotEmpty ? params : null);
  }

  Future<Map<String, dynamic>> getKelayakanList({String? tahunAjaran, String? semester}) async {
    final params = <String, String>{};
    if (tahunAjaran != null && tahunAjaran.isNotEmpty) params['tahun_ajaran'] = tahunAjaran;
    if (semester != null && semester.isNotEmpty) params['semester'] = semester;
    return await get('kelayakan', queryParams: params.isNotEmpty ? params : null);
  }

  Future<Map<String, dynamic>> getKelayakanDetail(int id) async {
    return await get('kelayakan/$id');
  }

  Future<Map<String, dynamic>> getKelayakanCreate() async {
    return await get('kelayakan/create');
  }

  Future<Map<String, dynamic>> kelayakanProses({
    required int mahasiswaId,
    required int matakuliahId,
    required String tahunAjaran,
    required String semester,
  }) async {
    return await post('kelayakan/proses', body: {
      'mahasiswa_id': mahasiswaId,
      'matakuliah_id': matakuliahId,
      'tahun_ajaran': tahunAjaran,
      'semester': semester,
    });
  }

  Future<Map<String, dynamic>> kelayakanBatch({
    required String tahunAjaran,
    required String semester,
  }) async {
    return await post('kelayakan/batch', body: {
      'tahun_ajaran': tahunAjaran,
      'semester': semester,
    });
  }

  Future<Map<String, dynamic>> getMatakuliahByMahasiswa(int mahasiswaId) async {
    return await get('kelayakan/mahasiswa/$mahasiswaId/matakuliah');
  }

  Future<Map<String, dynamic>> getMatakuliahList() async {
    return await get('matakuliah');
  }

  Future<Map<String, dynamic>> getProdiList() async {
    return await get('prodi');
  }

  Future<Map<String, dynamic>> getTahunAjaranList() async {
    return await get('tahun-ajaran');
  }

  Future<Map<String, dynamic>> getDosenList() async {
    return await get('dosen');
  }
}
