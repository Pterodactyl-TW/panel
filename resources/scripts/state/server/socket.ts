import { Action, action } from 'easy-peasy';
import { Websocket } from '@/plugins/Websocket';
import { DemoWebsocket } from '@/plugins/DemoWebsocket';

export type ServerSocket = Websocket | DemoWebsocket;

export interface SocketStore {
    instance: ServerSocket | null;
    connected: boolean;
    setInstance: Action<SocketStore, ServerSocket | null>;
    setConnectionState: Action<SocketStore, boolean>;
}

const socket: SocketStore = {
    instance: null,
    connected: false,
    setInstance: action((state, payload) => {
        state.instance = payload;
    }),
    setConnectionState: action((state, payload) => {
        state.connected = payload;
    }),
};

export default socket;
