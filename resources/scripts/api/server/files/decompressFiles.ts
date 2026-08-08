import http from '@/api/http';

export default async (uuid: string, directory: string, file: string): Promise<void> => {
    await http.post(
        `/api/client/servers/${uuid}/files/decompress`,
        { root: directory, file },
        {
            timeout: 300000,
            timeoutErrorMessage: '此壓縮檔的解壓縮似乎需要較長時間，解壓縮完成後檔案將會出現。',
        }
    );
};
