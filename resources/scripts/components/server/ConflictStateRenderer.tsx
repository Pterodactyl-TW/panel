import React from 'react';
import { ServerContext } from '@/state/server';
import ScreenBlock from '@/components/elements/ScreenBlock';
import ServerInstallSvg from '@/assets/images/server_installing.svg';
import ServerErrorSvg from '@/assets/images/server_error.svg';
import ServerRestoreSvg from '@/assets/images/server_restore.svg';

export default () => {
    const status = ServerContext.useStoreState((state) => state.server.data?.status || null);
    const isTransferring = ServerContext.useStoreState((state) => state.server.data?.isTransferring || false);
    const isNodeUnderMaintenance = ServerContext.useStoreState(
        (state) => state.server.data?.isNodeUnderMaintenance || false
    );

    return status === 'installing' || status === 'install_failed' || status === 'reinstall_failed' ? (
        <ScreenBlock
            title={'正在執行安裝程式'}
            image={ServerInstallSvg}
            message={'你的伺服器應該很快就會準備就緒，請在幾分鐘後再試一次。'}
        />
    ) : status === 'suspended' ? (
        <ScreenBlock
            title={'伺服器已停權'}
            image={ServerErrorSvg}
            message={'此伺服器已被停權，無法存取。'}
        />
    ) : isNodeUnderMaintenance ? (
        <ScreenBlock
            title={'節點維護中'}
            image={ServerErrorSvg}
            message={'此伺服器所在的節點目前正在維護中。'}
        />
    ) : (
        <ScreenBlock
            title={isTransferring ? '轉移中' : '正在從備份還原'}
            image={ServerRestoreSvg}
            message={
                isTransferring
                    ? '你的伺服器正在轉移至新節點，請稍後再確認。'
                    : '你的伺服器目前正在從備份還原，請在幾分鐘後再確認。'
            }
        />
    );
};
