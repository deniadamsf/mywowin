import 'dart:convert';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/constants/api_constants.dart';

class ChatState {
  final bool isLoading;
  final List<dynamic> messages;
  final String? errorMessage;

  ChatState({
    this.isLoading = false,
    this.messages = const [],
    this.errorMessage,
  });

  ChatState copyWith({bool? isLoading, List<dynamic>? messages, String? errorMessage}) {
    return ChatState(
      isLoading: isLoading ?? this.isLoading,
      messages: messages ?? this.messages,
      errorMessage: errorMessage,
    );
  }
}

class ChatNotifier extends Notifier<ChatState> {
  @override
  ChatState build() {
    return ChatState();
  }

  // 1. Tarik Riwayat Chat
  Future<void> fetchChats() async {
    state = state.copyWith(isLoading: true, errorMessage: null);
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      final response = await http.get(
        Uri.parse('$baseUrl/chats'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        state = state.copyWith(isLoading: false, messages: data['data'] ?? []);
      } else {
        state = state.copyWith(isLoading: false, errorMessage: 'Gagal memuat pesan');
      }
    } catch (e) {
      state = state.copyWith(isLoading: false, errorMessage: 'Koneksi bermasalah');
    }
  }

  // 2. Kirim Pesan Baru
  Future<bool> sendMessage(String message) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      // Tambahkan pesan secara lokal dulu agar UI terasa cepat (Optimistic UI)
      final tempMessage = {
        'id': DateTime.now().millisecondsSinceEpoch,
        'sender_role': 'user',
        'message': message,
        'created_at': DateTime.now().toIso8601String(),
      };
      state = state.copyWith(messages: [...state.messages, tempMessage]);

      final response = await http.post(
        Uri.parse('$baseUrl/chats'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: {'message': message},
      );

      if (response.statusCode == 201) {
        // Tarik ulang dari server untuk memastikan ID dari database sinkron
        await fetchChats();
        return true;
      }
      return false;
    } catch (e) {
      return false;
    }
  }
}

final chatProvider = NotifierProvider<ChatNotifier, ChatState>(() {
  return ChatNotifier();
});