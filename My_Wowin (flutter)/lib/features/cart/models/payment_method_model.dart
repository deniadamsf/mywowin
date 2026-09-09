class BankAccountModel {
  final String id;
  final String bankName;
  final String accountNumber;
  final String accountHolder;
  final bool isActive;

  BankAccountModel({
    required this.id,
    required this.bankName,
    required this.accountNumber,
    required this.accountHolder,
    this.isActive = true,
  });

  factory BankAccountModel.fromJson(Map<String, dynamic> json) {
    return BankAccountModel(
      id: json['id']?.toString() ?? '',
      bankName: json['bank_name']?.toString() ?? '',
      accountNumber: json['account_number']?.toString() ?? '',
      accountHolder: json['account_holder']?.toString() ?? '',
      isActive: json['is_active'] == true || json['is_active'] == 1 || json['is_active'] == '1',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'bank_name': bankName,
      'account_number': accountNumber,
      'account_holder': accountHolder,
      'is_active': isActive,
    };
  }
}

class PaymentMethodModel {
  final String code;
  final String name;
  final String description;
  final bool isActive;
  final List<BankAccountModel> bankAccounts;
  final String? phoneNumber;

  PaymentMethodModel({
    required this.code,
    required this.name,
    required this.description,
    this.isActive = true,
    this.bankAccounts = const [],
    this.phoneNumber,
  });

  factory PaymentMethodModel.fromJson(Map<String, dynamic> json) {
    final rawBanks = json['bank_accounts'];
    List<BankAccountModel> banks = [];
    if (rawBanks is List) {
      banks = rawBanks
          .map((b) => BankAccountModel.fromJson(Map<String, dynamic>.from(b as Map)))
          .toList();
    }

    return PaymentMethodModel(
      code: json['code']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
      isActive: json['is_active'] == true || json['is_active'] == 1 || json['is_active'] == '1',
      bankAccounts: banks,
      phoneNumber: json['phone_number']?.toString(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'code': code,
      'name': name,
      'description': description,
      'is_active': isActive,
      'bank_accounts': bankAccounts.map((b) => b.toJson()).toList(),
      'phone_number': phoneNumber,
    };
  }
}

class ShippingVoucherModel {
  final String code;
  final String name;
  final String description;
  final double minPurchase;
  final double discountAmount;
  final double baseRatePerKg; // Jatim & Madura (Rp 4.500)
  final double rateJawaNonJatim; // Pulau Jawa Lainnya (Rp 9.500)
  final bool isActive;

  ShippingVoucherModel({
    required this.code,
    required this.name,
    required this.description,
    this.minPurchase = 10000.0,
    this.discountAmount = 4500.0,
    this.baseRatePerKg = 4500.0,
    this.rateJawaNonJatim = 9500.0,
    this.isActive = true,
  });

  factory ShippingVoucherModel.fromJson(Map<String, dynamic> json) {
    return ShippingVoucherModel(
      code: json['code']?.toString() ?? 'ONGKIR4500',
      name: json['name']?.toString() ?? 'Voucher Diskon Ongkir Rp 4.500',
      description: json['description']?.toString() ?? 'Min. belanja Rp 10.000 (Maksimal diskon Rp 4.500)',
      minPurchase: double.tryParse(json['min_purchase']?.toString() ?? '') ?? 10000.0,
      discountAmount: double.tryParse(json['discount_amount']?.toString() ?? '') ?? 4500.0,
      baseRatePerKg: double.tryParse(json['base_rate_per_kg']?.toString() ?? '') ?? 4500.0,
      rateJawaNonJatim: double.tryParse(json['rate_jawa_non_jatim']?.toString() ?? '') ?? 9500.0,
      isActive: json['is_active'] == true || json['is_active'] == 1 || json['is_active'] == '1',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'code': code,
      'name': name,
      'description': description,
      'min_purchase': minPurchase,
      'discount_amount': discountAmount,
      'base_rate_per_kg': baseRatePerKg,
      'rate_jawa_non_jatim': rateJawaNonJatim,
      'is_active': isActive,
    };
  }
}
