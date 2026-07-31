import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:simak_mobile/main.dart';

void main() {
  testWidgets('App starts on splash screen', (tester) async {
    SharedPreferences.setMockInitialValues({});

    await tester.pumpWidget(const SimakApp());

    expect(find.text('SIMAK Mobile'), findsOneWidget);
    expect(find.byType(CircularProgressIndicator), findsOneWidget);
  });

  testWidgets('Navigates to login when no saved session', (tester) async {
    SharedPreferences.setMockInitialValues({});

    await tester.pumpWidget(const SimakApp());
    await tester.pump(const Duration(seconds: 1));
    await tester.pump(const Duration(seconds: 1));

    expect(find.text('Masuk'), findsOneWidget);
    expect(find.text('SIMAK Mobile'), findsOneWidget);
  });
}
