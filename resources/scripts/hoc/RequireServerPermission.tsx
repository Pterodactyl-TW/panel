import React from 'react';
import Can from '@/components/elements/Can';
import { ServerError } from '@/components/elements/ScreenBlock';

export interface RequireServerPermissionProps {
    permissions: string | string[];
}

const RequireServerPermission: React.FC<RequireServerPermissionProps> = ({ children, permissions }) => {
    return (
        <Can
            action={permissions}
            renderOnError={<ServerError title={'拒絕存取'} message={'你沒有權限存取此頁面。'} />}
        >
            {children}
        </Can>
    );
};

export default RequireServerPermission;
