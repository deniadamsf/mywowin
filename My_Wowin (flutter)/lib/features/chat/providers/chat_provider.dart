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

  // 1. Tarik Riwayat Chat (dengan dukungan silent polling)
  Future<void> fetchChats({bool silent = false}) async {
    if (!silent && state.messages.isEmpty) {
      state = state.copyWith(isLoading: true, errorMessage: null);
    }
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token == null || token.isEmpty) {
        if (!silent) state = state.copyWith(isLoading: false);
        return;
      }

      final response = await http.get(
        Uri.parse('$baseUrl/chats'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer $token',
        },
      ).timeout(const Duration(seconds: 6));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final List<dynamic> newMessages = data['data'] ?? [];

        // Deteksi apakah ada pesan baru atau perbedaan pesan terakhir
        bool hasChanges = newMessages.length != state.messages.length;
        if (!hasChanges && newMessages.isNotEmpty && state.messages.isNotEmpty) {
          hasChanges = newMessages.last['id'] != state.messages.last['id'];
        }

        if (hasChanges || (!silent && state.messages.isEmpty)) {
          state = state.copyWith(isLoading: false, messages: newMessages);
        } else if (!silent) {
          state = state.copyWith(isLoading: false);
        }
      } else {
        if (!silent) {
          state = state.copyWith(isLoading: false, errorMessage: 'Gagal memuat pesan');
        }
      }
    } catch (e) {
      if (!silent) {
        state = state.copyWith(isLoading: false, errorMessage: 'Koneksi bermasalah');
      }
    }
  }

  // 2. Kirim Pesan Baru
  Future<bool> sendMessage(String message) async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');
      if (token == null || token.isEmpty) return false;

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
        // Tarik ulang secara hening agar ID tersinkron dengan database
        await fetchChats(silent: true);
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