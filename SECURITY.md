# Security Policy

## Supported Versions

Security updates will be provided for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 3.0.x   | ✅ Yes            |
| 2.x.x   | ❌ No (deprecated) |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability, please follow these steps:

### 1. Contact Information

Send vulnerability reports to:
- **Email**: https://jmoorewv.com/contact/
- **Subject**: "Smart Internal Links Security Vulnerability"

### 2. Required Information

Please include the following in your report:

- **Description** of the vulnerability
- **Steps to reproduce** the issue
- **Potential impact** assessment
- **Affected versions** (if known)
- **Suggested fix** (if you have one)
- **Your contact information** for follow-up

### 3. Response Timeline

- **Initial Response**: Within 48 hours
- **Vulnerability Assessment**: Within 7 days
- **Fix Development**: Timeline varies based on severity
- **Release**: Security patches released as soon as possible

### 4. Disclosure Process

1. **Private Disclosure**: Report received and acknowledged
2. **Investigation**: Vulnerability verified and assessed
3. **Fix Development**: Patch developed and tested
4. **Coordinated Release**: Fix released with security advisory
5. **Public Disclosure**: Details shared after fix is available

## Security Measures

### Current Protections

The plugin implements several security measures:

- **Input Sanitization**: All user inputs sanitized using WordPress functions
- **Output Escaping**: All outputs properly escaped
- **Nonce Verification**: All forms protected with WordPress nonces
- **Capability Checks**: Admin functions require proper permissions
- **SQL Injection Prevention**: All queries use `$wpdb->prepare()`
- **XSS Prevention**: Proper output escaping throughout

### Security Testing

Regular security testing includes:

- **Static Analysis**: Code reviewed for common vulnerabilities
- **Input Validation**: All inputs tested for malicious content
- **Permission Testing**: Admin functions tested for privilege escalation
- **SQL Injection Testing**: Database queries tested for injection flaws

## Vulnerability Types

### High Priority

- Remote code execution
- SQL injection
- Cross-site scripting (XSS)
- Authentication bypass
- Privilege escalation

### Medium Priority

- Cross-site request forgery (CSRF)
- Information disclosure
- Denial of service
- Path traversal

### Low Priority

- Minor information leaks
- Non-exploitable edge cases

## Security Best Practices

### For Users

- **Keep Updated**: Always use the latest plugin version
- **Regular Backups**: Maintain recent site backups
- **Staging Testing**: Test updates on staging sites first
- **Monitor Logs**: Watch for unusual activity in WordPress logs
- **Principle of Least Privilege**: Only grant necessary user permissions

### For Developers

- **Secure Coding**: Follow WordPress security guidelines
- **Input Validation**: Never trust user input
- **Output Escaping**: Always escape output
- **Capability Checks**: Verify user permissions
- **Nonce Usage**: Use nonces for all forms
- **SQL Preparation**: Use prepared statements

## Acknowledgments

I appreciate security researchers who responsibly disclose vulnerabilities. Contributors will be credited in:

- Plugin changelog
- Security advisories
- Public acknowledgments (with permission)

## Contact

For security-related questions or concerns:

- **Security Email**: https://jmoorewv.com/contact/
- **General Contact**: Through jmoorewv.com
- **GitHub**: Create private security advisory

---

**Note**: This security policy applies to the Smart Internal Links plugin codebase. For WordPress core security issues, please follow WordPress.org's security reporting procedures.
