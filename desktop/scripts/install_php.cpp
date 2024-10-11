#include <iostream>
#include <string>
#include <cstdlib>
#include <unistd.h>

void download_and_unzip(const std::string& url, const std::string& output_path) {
    std::string curl_command = "curl -L -o \"" + output_path + "\" \"" + url + "\"";
    std::string unzip_command = "cd php && unzip -o \"" + output_path.substr(output_path.find_last_of('/') + 1) + "\"";
    
    if (system(curl_command.c_str()) == 0) {
        system(unzip_command.c_str());
        std::cout << "Unzipped successfully.\n";
    } else {
        std::cerr << "Failed to acquire PHP.\n";
    }
}

int main() {
    if (access("./php/php", F_OK) == 0) {
        std::cout << "Detected PHP binary.\n";
        return 0;
    } 

#ifdef __linux__
    std::cout << "Detected Linux OS.\n";
    std::string php_download_url = "https://github.com/NativePHP/php-bin/raw/refs/heads/main/bin/linux/x64/php-8.2.zip";
    download_and_unzip(php_download_url, "./php/php-8.2.zip");

#elif __APPLE__
    std::cout << "Detected macOS.\n";

    #if defined(__x86_64__)
        std::cout << "Detected x86_64 architecture.\n";
        std::string php_download_url = "https://github.com/NativePHP/php-bin/raw/refs/heads/main/bin/mac/x86/php-8.2.zip";
        download_and_unzip(php_download_url, "./php/php-8.2.zip");

    #elif defined(__arm64__) || defined(__aarch64__)
        std::cout << "Detected ARM64 architecture.\n";
        std::string php_download_url = "https://github.com/NativePHP/php-bin/raw/refs/heads/main/bin/mac/arm64/php-8.2.zip";
        download_and_unzip(php_download_url, "./php/php-8.2.zip");

    #else
        std::cerr << "Unknown architecture on macOS.\n";
        return 1;
    #endif

#else
    std::cerr << "Operating system not supported.\n";
    return 1;
#endif

    return 0;
}