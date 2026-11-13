<?php
/** Locks the recovery record and account; expiry/use are checked in the transaction. */
function reset_password(PDO $pdo, string $token, string $password): bool {
    if (!preg_match('/\A[a-f0-9]{64}\z/D', $token) || strlen($password) < 12 || strlen($password) > 72) return false;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('SELECT id_recovery, id_usuario, expire_time, status FROM tb_recovery WHERE hash = ? FOR UPDATE');
        $stmt->execute([hash('sha256', $token)]);
        $recovery = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$recovery || (int) $recovery['status'] !== 1 || strtotime($recovery['expire_time']) <= time()) { $pdo->rollBack(); return false; }
        $stmt = $pdo->prepare('UPDATE tb_usuario SET senha = ?, alterado_por = ?, data_ultima_alteracao = CURRENT_TIMESTAMP WHERE id_usuario = ? AND status = 1');
        $stmt->execute([password_hash($password, PASSWORD_DEFAULT), 'PASSWORD_RESET', $recovery['id_usuario']]);
        if ($stmt->rowCount() !== 1) { $pdo->rollBack(); return false; }
        $stmt = $pdo->prepare('UPDATE tb_recovery SET status = 0 WHERE id_usuario = ?');
        $stmt->execute([$recovery['id_usuario']]);
        $pdo->commit();
        return true;
    } catch (Throwable $error) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw new RuntimeException('Não foi possível atualizar a senha.');
    }
}
