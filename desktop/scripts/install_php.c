#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main() {
	if (access("./php/php", F_OK) == 0) {
    	printf("Detected PHP binary.\n");
		return 0;
	} 

	#ifdef __linux__
		printf("Detected Linux OS.\n");

		const char *php_download_url = "https://github.com/NativePHP/php-bin/raw/refs/heads/main/bin/linux/x64/php-8.2.zip";
		char curl_command[256];
		char unzip_command[256];
		snprintf(curl_command, sizeof(curl_command), "curl -L -o \"%s\" \"%s\"", "./php/php-8.2.zip", php_download_url);
		if (system(curl_command) == 0) {
			// Unzip using `unzip` command
			snprintf(unzip_command, sizeof(unzip_command), "cd php && unzip -o \"%s\"", "php-8.2.zip");
			system(unzip_command);
			printf("Unzipped successfully on Linux.\n");
		} else {
			printf("Failed to acquire PHP on Linux.\n");
		}
	#elif __APPLE__
		printf("Detected macOS.\n");

		#if defined(__x86_64__)
			printf("Detected x86_64 architecture.\n");

			const char *php_download_url = "https://github.com/NativePHP/php-bin/raw/refs/heads/main/bin/mac/x86/php-8.2.zip";
			char curl_command[256];
			char unzip_command[256];
			// Use `curl` to download the file from Github
			snprintf(curl_command, sizeof(curl_command), "curl -L -o \"%s\" \"%s\"", "./php/php-8.2.zip", php_download_url);
			if (system(curl_command) == 0) {
				// Use `unzip` for x86_64 macOS
				snprintf(unzip_command, sizeof(unzip_command), "cd php && unzip -o \"%s\"", "php-8.2.zip");
				system(unzip_command);
				printf("Unzipped successfully on x86_64 macOS.\n");
			} else {
				printf("Failed to acquire PHP on x86_64 macOS.\n");
			}
		#elif defined(__arm64__) || defined(__aarch64__)
			const char *php_download_url = "https://github.com/NativePHP/php-bin/raw/refs/heads/main/bin/mac/arm64/php-8.2.zip";
			printf("Detected ARM64 architecture.\n");
			char curl_command[256];
			char unzip_command[256];
			// Use `curl` to download the file from Github
			snprintf(curl_command, sizeof(curl_command), "curl -L -o \"%s\" \"%s\"", "./php/php-8.2.zip", php_download_url);
			if (system(curl_command) == 0) {
				// Use `unzip` for ARM macOS
				snprintf(unzip_command, sizeof(unzip_command), "cd php && unzip -o \"%s\"", "php-8.2.zip");
				system(unzip_command);
				printf("Unzipped successfully on ARM64 macOS.\n");
			} else {
				printf("Failed to acquire PHP on ARM64 macOS.\n");
			}
    #else
        printf("Unknown architecture on macOS.\n");
    #endif
	#else
		printf("Operating system not supported.\n");
		return 1;
	#endif

	return 0;
}